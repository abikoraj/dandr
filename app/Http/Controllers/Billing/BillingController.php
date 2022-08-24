<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Customer;
use App\Models\Distributer;
use App\Models\FiscalYear;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $centers = DB::table('centers')->get(['id', 'name']);
        $hasTable = $request->filled('table');
        $table_id = $request->input('table');
        return view('admin.billing.index', compact('centers', 'hasTable', 'table_id'));
    }

    public function del($id)
    {
        $billItems = DB::select('select b.center_id,bi.item_id,bi.qty from bill_items bi join bills b on b.id=bi.bill_id where b.id=?', [$id]);
        // dd($billItems);
        foreach ($billItems as $key => $billItem) {
            maintainStock($billItem->item_id, $billItem->qty, $billItem->center_id);
        }
        DB::delete('update bills set is_canceled =1 where id=?', [$id]);
    }

    public function list(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $bills_query = DB::table('bills');
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $fy = FiscalYear::find($request->fy);
            $range = [];
            $data = [];
            $date = 1;
            $title = "";
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $bills_query = $bills_query->where('date', '=', $date);
                $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 6) {
                $range[1] = $fy->startdate;
                $range[2] = $fy->enddate;
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            }
            if ($request->customer_id != -1) {
                $bills_query = $bills_query->where('customer_id', $request->customer_id);
            }
            if ($request->filled('bill_no')) {
                $bills_query = $bills_query->where('billno', $request->bill_no);
            }


            if ($request->canceled == 0) {
                $bills_query = $bills_query->where('is_canceled', 0);
            }
            $bills = $bills_query->select(
                DB::raw("id,(select group_concat(concat(name,' x ',qty) SEPARATOR ', ')  from bill_items where bill_items.bill_id=bills.id) as billitems,name,grandtotal,billno,center_id,date,is_canceled")
            )->get();
            return view('admin.billing.billlist', compact('bills'));
        } else {
            return view('admin.billing.list');
        }
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $bill = new Bill();

        $date = str_replace('-', '', $request->date);
        // dd($date);

        if ($request->id != -1) {
            $customer = Customer::where('id', $request->id)->first();
            $user = User::where('id', $customer->user_id)->first();
            $bill->name = $user->name;
            $bill->address = $user->address;
            $bill->phone = $user->phone;
            $bill->user_id = $user->id;
            $ledger = new LedgerManage($user->id);
            $paidamount = $request->paid > $request->net ? $request->net : $request->paid;
        } else {
            $bill->name = 'Cash Sales';
        }
        $bill->table_id = $request->table_id;
        $bill->grandtotal = $request->gross;
        $bill->paid = $request->paid;
        $bill->due = $request->due;
        $bill->dis = $request->dis;
        $bill->net_total = $request->net;
        $bill->return = $request->return;
        $bill->date = $date;
        $bill->center_id = $request->center_id;

        $bill->save();

        if ($request->id != -1) {
            $ledger->addLedger('Purchase ', 1, $request->net, $date, 130, $bill->id);

            if ($request->paid > 0) {
                $ledger->addLedger('Paid Amount', 2, $paidamount, $date, 131, $bill->id);
            }
        }
        // dd($request->billitems);
        $billitem = [];
        foreach ($request->billitems as $t) {
            // dd($bill->id);
            $item = new BillItem();
            $i = (object)$t;
            $item->item_id = $i->id;
            $item->name = $i->name;
            $item->rate = $i->rate;
            $item->qty = $i->qty;
            $item->total = $i->total;
            $item->bill_id = $bill->id;
            $item->amount = 0;
            $item->save();
            array_push($billitem, $item);
            maintainStock($item->item_id, $item->qty, $bill->center_id, 'out');
        }
        $bill->items = $billitem;
        if ($bill->table_id != null) {
            DB::update('update tables set data=null where id=?', [$bill->table_id]);
        }
        return response()->json(['status' => true,'id'=>$bill->id]);
    }


    public function detail($id)
    {
        $bill = Bill::find($id);

        return view('admin.billing.detail', compact('bill'));
    }
}
