<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\ChalanDue;
use App\Models\ChalanduePayment;
use App\Models\Employee;
use App\Models\EmployeeChalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChalanClosingController extends Controller
{
    const notes = [1000, 500, 100, 50, 25, 20, 10, 5, 2, 1];

    public function index(Request $request, $id)
    {
        $chalan = EmployeeChalan::where('id', $id)->first();
        $payments = DB::table('chalan_payments')->where('employee_chalan_id', $id)->get();
        $chalanItems = DB::table('chalan_items')
            ->join('items', 'items.id', '=', 'chalan_items.item_id')
            ->where('employee_chalan_id', $id)
            ->select('chalan_items.*', 'items.title', 'items.unit')
            ->get();
        $sellItems = DB::table('chalan_sales')
            ->join('items', 'items.id', '=', 'chalan_sales.item_id')
            ->where('employee_chalan_id', $id)
            ->select('chalan_sales.*', 'items.title', 'items.unit')
            ->get();
        $user_ids = array_merge($payments->pluck('user_id')->toArray(), $sellItems->pluck('user_id')->toArray());

        $users = DB::table('users')->whereIn('id', $user_ids)->get(['id', 'name']);

        foreach ($users as $key => $user) {
            $user->sales = $sellItems->where('user_id', $user->id);
            $user->payments = $payments->where('user_id', $user->id);
            $user->sales_amount = $sellItems->where('user_id', $user->id)->sum('total');
            $user->payments_amount = $payments->where('user_id', $user->id)->sum('amount');
            $balance = $user->sales_amount - $user->payments_amount;
            $user->due = $balance > 0 ? $balance : 0;
            $user->balance = $balance < 0 ? (-1 * $balance) : 0;
        }

        foreach ($chalanItems as $key => $chalanItem) {
            $chalanItem->sold = $sellItems->where('item_id', $chalanItem->item_id)->sum('qty');
            $chalanItem->newremaning = $chalanItem->qty - $chalanItem->sold - $chalanItem->wastage;
        }
        $notes = self::notes;
        $banks = DB::table('banks')->get(['name', 'account_id', 'id']);

        if ($request->getMethod() == "GET") {

            return view('admin.chalan.closing.index', compact('chalan', 'users', 'chalanItems', 'notes', 'banks'));
        } else {
            if ($chalan->closed == 1) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Chalan is already closed'
                ]);
            }
            foreach ($chalanItems as $key => $chalanItem) {
                if ($chalanItem->newremaning > 0) {
                    if ($chalanItem->newremaning != $chalanItem->remaning) {

                        DB::update('update chalan_items set remaning=? where id=?', [$chalanItem->newremaning, $chalanItem->id]);
                        $newStock = $chalanItem->newremaning - $chalanItem->remaning;
                        if ($newStock > 0) {
                            maintainStock($chalanItem->item_id, $newStock, $chalanItem->center_id, 'in');
                        } elseif ($newStock < 0) {
                            maintainStock($chalanItem->item_id, (-1 * $newStock), $chalanItem->center_id, 'out');
                        }
                    }
                }
            }

            //XXX identifire 
            // 403 = sales employee chalan sales
            // 404 = sales payment while chalan sales

            $collections = [
                "bank" => [],
                "notes" => []
            ];

            $totalCollection = 0;
            $noteCollection = 0;
            $bankCollection = 0;

            foreach ($notes as $key => $note) {
                if ($request->filled('note_' . $note)) {
                    $noteAmount = $request->input('note_' . $note);
                    if ($noteAmount > 0) {
                        array_push($collections['notes'], (object)['note' => $note, 'amount' => $noteAmount]);
                        $noteCollection += $noteAmount;
                    }
                }
            }

            foreach ($banks as $key => $bank) {
                if ($request->filled('bank_' . $bank->id)) {
                    $bankAmount = $request->input('bank_' . $bank->id);
                    if ($bankAmount > 0) {
                        array_push($collections['bank'], (object)['account_id' => $bank->account_id, 'name' => $bank->name, 'bank_id' => $bank->id, 'amount' => $bankAmount]);
                        $bankCollection += $bankAmount;
                    }
                }
            }

            $totalCollection = $noteCollection + $bankCollection;


            if ($totalCollection != $users->sum('payments_amount')) {
                return response()->json([
                    'status' => false,
                    'msg' => 'Collection amount and total payment doesnot matches'
                ]);
            }

            if (hasPay()) {

                if ($noteCollection > 0) {
                    pushCASH(2, $noteCollection, 405, $chalan->date, 'To Chalan Sales/Collection', $chalan->id);
                }

                foreach ($collections['bank'] as $key => $bank) {
                    pushBANK($bank->account_id, 2, $bank->amount, 406, $chalan->date, 'To Chalan Sales/Collection', $chalan->id);
                }
            }

            foreach ($users as $key => $user) {
                $ledger = new LedgerManage($user->id);
                foreach ($user->sales as $key => $sale) {
                    if (DB::table('ledgers')->where('identifire', 403)->where('foreign_key', $sale->id)->count() == 0) {
                        $ledger->addLedger($sale->title . ' X ' . $sale->qty . ' ' . $sale->unit, 2, $sale->total, $chalan->date, 403, $sale->id);
                    }
                }

                foreach ($user->payments as $key => $payment) {
                    if (DB::table('ledgers')->where('identifire', 404)->where('foreign_key', $payment->id)->count() == 0) {
                        $ledger->addLedger('Amount Received', 1, $payment->amount, $chalan->date, 404, $payment->id);
                    }
                }

                if ($user->due > 0) {
                    $chalanDue = new ChalanDue();
                    $chalanDue->user_id = $user->id;
                    $chalanDue->employee_id = $chalan->user_id;
                    $chalanDue->amount = $user->due;
                    $chalanDue->date = $chalan->date;
                    $chalanDue->employee_chalan_id = $chalan->id;
                    $chalanDue->save();
                }
                $amountRemaning = $user->balance;
                if ($amountRemaning > 0) {
                    $dues = DB::select('select c.* from
                    (select chalan_dues.amount,chalan_dues.date,chalan_dues.id,ifnull((select sum(amount) from chalandue_payments where chalan_due_id=chalan_dues.id),0) as paid from chalan_dues where user_id=? ) c
                    where c.amount>c.paid', [$user->id]);
                    $amountRemaning = $payment->amount;
                    foreach ($dues as $key => $due) {
                        $remaning = $due->amount - $due->paid;
                        $duePayment = new ChalanduePayment();
                        $duePayment->chalan_due_id = $due->id;
                        $duePayment->identifire = 407;
                        $duePayment->foreign_key = $chalan->id;
                        $duePayment->date =$chalan->date;
                        if ($remaning > $amountRemaning || $remaning == $amountRemaning) {
                            $duePayment->amount = $amountRemaning;
                            $duePayment->save();
                            break;
                        } elseif ($remaning < $amountRemaning) {
                            $duePayment->amount = $remaning;
                            $duePayment->save();
                            $amountRemaning -= $remaning;
                        }
                    }
                }
            }

            $chalan->closed = 1;
            $chalan->notes = json_encode($collections, JSON_NUMERIC_CHECK);
            $chalan->save();
            return response()->json([
                'status' => true,
                'url' => route('admin.chalan.chalan.final.details', ['id' => $chalan->id])
            ]);
        }
    }
}
