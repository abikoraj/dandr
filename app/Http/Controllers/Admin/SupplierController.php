<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\BillExpenses;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\Ledger;
use App\Models\Supplierbill;
use App\Models\Supplierbillitem;
use App\Models\Supplierpayment;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        return view('admin.supplier.index');
    }

    public function list()
    {
        $supplier = User::latest()->where('role', 3)->get();
        return view('admin.supplier.list', ['supplier' => $supplier]);
    }

    public function add(Request $request)
    {
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 3;
        $user->password = bcrypt($request->phone);
        $user->save();
        return view('admin.supplier.single', compact('user'));
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->id)->where('role', 3)->first();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 3;
        $user->password = bcrypt($request->phone);
        $user->save();
        return view('admin.supplier.single', compact('user'));
    }

    public function delete(Request $request)
    {
        $user = User::where('id', $request->id)->where('role', 3)->first();
        $user->delete();
        return response()->json('Delete successfully !');
    }


    //XXX supplier bill controllers

    public function indexBill()
    {
        return view('admin.supplier.bill.index');
    }

    public function addBill(Request $request)
    {
        // dd($request->all());
        if ($request->getMethod() == "POST") {

            // dd($request->all());
            $date = str_replace('-', '', $request->date);
            $bill = new Supplierbill();
            $bill->billno = $request->billno;
            $bill->date = $date;
            $bill->total = 0;
            $bill->paid = $request->ipaid;
            $bill->due = 0;
            $bill->user_id = $request->user_id;
            $bill->transport_charge = 0;
            $bill->save();
            $traker =  $request->counter;
            $total = 0;
            foreach ($traker as $key => $value) {
                $billItem = new Supplierbillitem();
                $billItem->title = $request->input('ptr_' . $value);
                $billItem->rate = $request->input('rate_' . $value);
                $billItem->qty = $request->input('qty_' . $value);
                $billItem->remaning = $request->input('qty_' . $value);
                $billItem->item_id = $request->input('item_id_' . $value);
                if ($request->filled('has_exp_' . $value)) {
                    $billItem->expiry_date = $request->input('exp_date_' . $value);
                    $billItem->has_expairy = 1;
                } else {
                    $billItem->has_expairy = 0;
                }
                $billItem->supplierbill_id = $bill->id;
                $billItem->save();
                //XXX Add Stock
                $item = Item::where('id', $billItem->item_id)->first();
                if ($item->trackstock) {
                    $item->stock += $billItem->qty;
                    $item->save();
                }
                $center_id = env('maincenter', -1);
                if ($center_id == -1) {
                    $center_id = DB::table('centers')->select('id')->first()->id;
                }
                $center_stock = CenterStock::where('center_id', $center_id)->where('item_id', $item->id)->first();
                if ($center_stock == null) {
                    $center_stock = new CenterStock();
                    $center_stock->center_id = $center_id;
                    $center_stock->item_id = $item->id;
                    $center_stock->wholesale = $item->wholesale;
                    $center_stock->rate = $item->sell_price;
                    $center_stock->amount = $billItem->qty;
                    $center_stock->save();
                } else {
                    $center_stock->amount +=  $billItem->qty;
                    $center_stock->save();
                }

                $total += $billItem->rate * $billItem->qty;
            }
            $bill->discount = $request->idiscount;
            $bill->taxable = $total - $bill->discount;
            $bill->tax = $request->itax;
            $bill->total = $bill->tax + $bill->taxable;
            $due = $bill->total - $bill->paid;
            $bill->due = $due >= 0 ? $due : 0;
            $bill->save();


            if ($request->filled('eis')) {

                foreach ($request->eis as $value) {
                    $ei = new BillExpenses([
                        'title' => $request->input('ei-title-' . $value),
                        'amount' => $request->input('ei-amount-' . $value),
                        'supplierbill_id' => $bill->id
                    ]);
                    $ei->save();
                }
            }
            $ledger = new LedgerManage($request->user_id);
            $ledger->addLedger('Item puchase from supplier bill no - ' . $bill->billno, 1, $bill->total, $date, '125', $bill->id);
            if ($request->ipaid > 0) {
                $ledger->addLedger('Paid to supplier for bill no - ' . $bill->billno, 2, $bill->paid, $date, '126', $bill->id);
            }
            return view('admin.supplier.bill.single', compact('bill'));
        } else {
            return view('admin.supplier.bill.add');
        }
    }

    public function cancelBill(Request $request)
    {
        $bill = Supplierbill::where('id', $request->bill_id)->first();
        $bill->canceled = 1;
        $bill->save();

        $billitems = Supplierbillitem::where('supplierbill_id', $bill->id)->get(['item_id','qty']);
        foreach ($billitems as $key => $bi) {
            # code...
            $item = Item::where('id', $bi->item_id)->select('id', 'title', 'wholesale', 'sell_price', 'stock', 'trackstock', 'points')->first();
            if ($item->trackstock == 1) {
                $item->stock -= $bi->qty;
                $item->save();
                $center_stock = CenterStock::where('center_id', env('maincenter'))->where('item_id', $item->id)->first();
                if ($center_stock == null) {
                    $center_stock = new CenterStock();
                    $center_stock->center_id = env('maincenter');
                    $center_stock->item_id = $item->id;
                    $center_stock->wholesale = $item->wholesale;
                    $center_stock->rate = $item->sell_price;
                    $center_stock->amount = -1 * $bi->qty;
                }else{
                    $center_stock->amount -=  $bi->qty;

                }
                $center_stock->save();
            }
        }

        $l1 = Ledger::where([
            'identifire' => 125,
            'foreign_key' => $bill->id
        ])->first();
        if ($l1 != null) {
            $l1->delete();
        }
        $l2 = Ledger::where([
            'identifire' => 126,
            'foreign_key' => $bill->id
        ])->first();
        if ($l2 != null) {
            $l2->delete();
        }
        return response('ok');
    }

    public function listBill(Request $request)
    {
        $bills_query = Supplierbill::latest();
        $year = $request->year;
        $month = $request->month;
        $week = $request->week;
        $session = $request->session;
        $type = $request->type;
        $range = [];
        $data = [];
        $date = 1;
        $title = "";
        if ($type == 0) {
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            $title .= "<span class='mx-2'>Session:" . $session . "</span>";
        } elseif ($type == 1) {
            $date = $date = str_replace('-', '', $request->date1);
            $bills_query = $bills_query->where('date', '=', $date);
            $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
        } elseif ($type == 2) {
            $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
            $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            $title .= "<span class='mx-2'>Week:" . $week . "</span>";
        } elseif ($type == 3) {
            $range = NepaliDate::getDateMonth($request->year, $request->month);
            $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
        } elseif ($type == 4) {
            $range = NepaliDate::getDateYear($request->year);
            $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
        } elseif ($type == 5) {
            $range[1] = str_replace('-', '', $request->date1);;
            $range[2] = str_replace('-', '', $request->date2);;
            $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>from:" . $request->date1 . "</span>";
            $title .= "<span class='mx-2'>To:" . $request->date2 . "</span>";
        }
        if ($request->user_id != -1) {
            $bills_query = $bills_query->where('user_id', $request->user_id);
        }
        $bills = $bills_query->where('canceled', 0)->get();
        // dd($bills,$bills_query->toSql(),$request->all(),$range);
        return view('admin.supplier.bill.list', compact('bills'));
    }



    public function billDetail(Supplierbill $bill)
    {
        // dd($bill);
        return view('admin.supplier.bill.detail', compact('bill'));
    }

    public function detail($id)
    {
        $user = User::where('id', $id)->where('role', 3)->first();
        return view('admin.supplier.detail', compact('user'));
    }
    public function loadDetail(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $week = $request->week;
        $session = $request->session;
        $type = $request->type;
        $range = [];
        $data = [];
        $date = 1;
        $title = "";
        $user = user::where('id', $request->user_id)->first();
        $ledger = Ledger::where('user_id', $request->user_id)->orderBy('date', 'asc')->orderBy('id', 'asc');
        if ($type == 0) {
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            $title .= "<span class='mx-2'>Session:" . $session . "</span>";
        } elseif ($type == 1) {
            $date = $date = str_replace('-', '', $request->date1);
            $ledger = $ledger->where('date', '=', $date);
            $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
        } elseif ($type == 2) {
            $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            $title .= "<span class='mx-2'>Week:" . $week . "</span>";
        } elseif ($type == 3) {
            $range = NepaliDate::getDateMonth($request->year, $request->month);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
        } elseif ($type == 4) {
            $range = NepaliDate::getDateYear($request->year);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
        } elseif ($type == 5) {
            $range[1] = str_replace('-', '', $request->date1);;
            $range[2] = str_replace('-', '', $request->date2);;
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>from:" . $request->date1 . "</span>";
            $title .= "<span class='mx-2'>To:" . $request->date2 . "</span>";
        }
        $base = 0;
        $prev = 0;
        $closing = 0;
        $arr = [];
        $ledgers = $ledger->orderBy('id', 'asc')->get();
        foreach ($ledgers as $key => $l) {

            if ($l->type == 1) {
                $base -= $l->amount;
            } else {
                $base += $l->amount;
            }
            if ($l->date < $range[1]) {
                $prev = $base;
            }
            if ($l->date >= $range[1] && $l->date <= $range[2]) {
                $l->amt = $base;
                $closing = $base;
                array_push($arr, $l);
            }
        }
        return view('admin.supplier.alldetail', compact('prev', 'title', 'user', 'arr'));
    }

    public function billItems(Request $request)
    {
        // dd($request->all());
        $billItem = Supplierbillitem::where('supplierbill_id', $request->bill_id)->get();
        // dd($billItem);
        return view('admin.supplier.bill.item', compact('billItem'));
    }

    public function updateBill(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $bill = Supplierbill::find($request->id);
        $bill->billno = $request->billno;
        $bill->date = $date;
        $bill->total = $request->total;
        $bill->paid = $request->paid;
        $bill->due = $request->total - $request->paid;
        $bill->user_id = $request->user_id;
        $bill->save();
        return view('admin.supplier.bill.single', compact('bill'));
    }

    public function deleteBill(Request $request)
    {
        $id = $request->id;
        $bill = Supplierbill::where('id', $id)->first();

        $billitems = Supplierbillitem::where('supplierbill_id', $bill->id)->get(['item_id','qty']);
        foreach ($billitems as $key => $bi) {
            # code...
            $item = Item::where('id', $bi->item_id)->select('id', 'title', 'wholesale', 'sell_price', 'stock', 'trackstock', 'points')->first();
            if ($item->trackstock == 1) {
                $item->stock -= $bi->qty;
                $item->save();
                $center_stock = CenterStock::where('center_id', env('maincenter'))->where('item_id', $item->id)->first();
                if ($center_stock == null) {
                    $center_stock = new CenterStock();
                    $center_stock->center_id = env('maincenter');
                    $center_stock->item_id = $item->id;
                    $center_stock->wholesale = $item->wholesale;
                    $center_stock->rate = $item->sell_price;
                    $center_stock->amount = -1 * $bi->qty;
                }else{
                    $center_stock->amount -=  $bi->qty;

                }
                $center_stock->save();
            }
        }

        $bill->delete();


        $data = [];
        $data[0] = Ledger::where('foreign_key', $id)->where('identifire', 125)->first();
        $ddd = Ledger::where('foreign_key', $id)->where('identifire', 126)->first();
        if ($ddd != null) {
            $data[1] = $ddd;
        }
        LedgerManage::delLedger($data);
        return response('ok');
    }

    // supplier payment
    public function payment()
    {
        return view('admin.supplier.pay.index');
    }

    public function due(Request $request)
    {
        $id = $request->id;
        $supplier = User::find($request->id);
        $payments = Ledger::where('user_id', $supplier->id)->where('identifire', '127')->get(['date', 'amount']);
        $supplier->balance = Ledger::where('user_id', $supplier->id)->where('type', 2)->sum('amount') - Ledger::where('user_id', $supplier->id)->where('type', 1)->sum('amount');

        return view('admin.supplier.pay.data', compact('supplier', 'id', 'payments'));
    }

    public function duePay(Request $request)
    {
        // $bills1=Distributorsell::where('distributer_id',$request->id)->where('deu','>',0)->get();
        $date = str_replace('-', '', $request->date);
        $amount = $request->amount;

        $payment = new Supplierpayment();
        // $paymentDatam
        $payment->amount = $request->amount;
        $payment->date = $date;
        $payment->payment_detail = $request->method ?? "";
        $payment->user_id = $request->id;
        $payment->save();
        $ledger = new LedgerManage($request->id);
        $ledger->addLedger("Payment to supplier", 2, $request->amount, $date, '127', $payment->id);
        $supplier = User::find($request->id);
        $id = $request->id;
        $payments = Ledger::where('user_id', $supplier->id)->where('identifire', '127')->get(['date', 'amount']);
        return view('admin.supplier.pay.data', compact('supplier', 'id','payments'));
    }



    // supplier previous balance

    public function previousBalance()
    {
        return view('admin.supplier.previous_balance.index');
    }

    public function previousBalanceAdd(Request $request)
    {
        // dd($request->all());
        $date = str_replace('-', '', $request->date);
        $user = User::where('id', $request->supplier_id)->first();
        $ledger = new LedgerManage($user->id);
        $l = $ledger->addLedger('previous Balance', $request->type, $request->amount, $date, '128');
        $l->name = $user->name;
        return view('admin.supplier.previous_balance.single', ['ledger' => $l]);
    }


    public function previousBalanceLoad(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $ledgers = User::join('ledgers', 'ledgers.user_id', '=', 'users.id')
            ->where('ledgers.date', $date)
            ->where('ledgers.identifire', 128)
            ->select('ledgers.id', 'ledgers.amount', 'ledgers.type', 'users.name')->get();
        return view('admin.supplier.previous_balance.list', compact('ledgers'));
    }
}
