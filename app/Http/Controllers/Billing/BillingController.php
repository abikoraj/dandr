<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\ConnectedItem;
use App\Models\Customer;
use App\Models\Distributer;
use App\Models\FiscalYear;
use App\Models\ItemCategory;
use App\Models\User;
use App\NepaliDate;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{

    public function loadConnectedItem(Request $request)
    {
        $connectedItem = ConnectedItem::where('id', $request->id)->first();
        $targetItem = DB::table('items')->where('id', $connectedItem->target_item_id)->first(['id', 'title']);
        $id=$targetItem->id;
        $finisedBatches = DB::table('batch_finisheds')->where('item_id',$id)->pluck('batch_id');
        $finisedBatcheSTR = count($finisedBatches) > 0 ? ' and id not in (' . implode(',', $finisedBatches->toArray()) . ")" : "";
        $batches = DB::select("select c.id as batch_id,c.amount,c.batch_no from(select id,(amount-
        ifnull((select sum(qty) from bill_items where batch_id=simple_manufacturing_items.id  and to_batch_id is null),0) -
        ifnull((select sum(qty) from sellitems where batch_id=simple_manufacturing_items.id ),0) -
        ifnull((select sum(s.amount) from simple_manufacturing_items s where s.batch_id=simple_manufacturing_items.id ),0)) as amount,batch_no
        from simple_manufacturing_items where item_id={$id} and batch_no is not null {$finisedBatcheSTR}) c where c.amount>0");
        $item=DB::table('items')->where('id',$connectedItem->item_id)->first(['id','title','sell_price']);
        $cats=ItemCategory::where('item_id',$connectedItem->item_id)->get();
        $rate=$cats->count()==0?$item->sell_price:$cats->first()->price;
        return view('admin.billing.multibatch_batch',compact('batches','targetItem','cats','item','rate'));
        
    }

    public function index(Request $request)
    {
        $centers = DB::table('centers')->get(['id', 'name']);
        $hasTable = $request->filled('table');
        $table_id = $request->input('table');
        $cats = DB::table('item_categories')->get(['id', 'name', 'item_id', 'price']);
        $hasBatches = [];
        if(env('user_oldpos_batch',false)){
            $products = DB::select('select distinct(item_id) from simple_manufacturing_items where batch_no is not null and type=2');
            $connected = DB::select('select distinct(item_id) from connected_items');
            foreach ($products as $key => $product) {
                array_push($hasBatches, $product->item_id);
            }
            foreach ($connected as $key => $product) {
                array_push($hasBatches, $product->item_id);
            }
        }


        // dd($hasBatches);



        return view('admin.billing.index', compact('centers', 'hasTable', 'table_id', 'cats', 'hasBatches'));
    }

    public function del($id)
    {
        $billItems = DB::select('select b.center_id,bi.item_id,bi.qty from bill_items bi join bills b on b.id=bi.bill_id where b.id=?', [$id]);
        // dd($billItems);
        foreach ($billItems as $key => $billItem) {
            maintainStock($billItem->item_id, $billItem->qty, $billItem->center_id);
        }

        DB::delete('update bills set is_canceled =1 where id=?', [$id]);
        PaymentManager::remove($id, 402);
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
        $bill->paid = $request->paid ?? 0;
        $bill->due = $request->due ?? 0;
        $bill->dis = $request->dis ?? 0;
        $bill->net_total = $request->net;
        $bill->return = $request->return;
        $bill->date = $date;
        $bill->center_id = $request->center_id;

        $bill->save();

        $titles = [];
        // dd($request->billitems);
        $billitem = [];

        foreach ($request->billitems as $t) {

            // dd($bill->id);
            $item = new BillItem();
            $i = (object)$t;
            $item->item_id = $i->id;
            $item->target_item_id = $i->target_item_id;
            $item->name = $i->name;
            $item->rate = $i->rate;
            $item->qty = $i->qty;
            $item->total = $i->total;
            $item->batch_id = $i->batch_id;
            $item->to_batch_id = $i->to_batch_id;
            $item->batch_id = $i->batch_id;
            $item->item_category_id = $i->item_category_id;
            // if($i->item_category_id!=null){

            //     $cat=DB::table('item_categories')->where('id',$i->item_category_id)->first(['name']);
            //     $item->name = $i->name.' - '.$cat->name;

            // }
            $item->bill_id = $bill->id;
            $item->amount = 0;
            $item->save();
            array_push($billitem, $item);
            maintainStock($item->item_id, $item->qty, $bill->center_id, 'out');
            array_push($titles, $item->name . " X " . $item->qty);
        }

        if ($request->id != -1) {
            $ledger->addLedger(implode(",", $titles), 2, $request->net, $date, 401, $bill->id);

            if ($request->paid > 0) {
                $ledger->addLedger('Received Amount', 1, $paidamount, $date, 402, $bill->id);
            }
        }
        $bill->items = $billitem;
        if ($bill->table_id != null) {
            DB::update('update tables set data=null where id=?', [$bill->table_id]);
        }
        if ($request->paid > 0) {
            new PaymentManager($request, $bill->id, 402, "To Counter Sales A/C", $date);
        }

        return response()->json(['status' => true, 'id' => $bill->id]);
    }


    public function detail($id)
    {
        $bill = Bill::find($id);
        $ledgers = [];

        if ($bill->paid > 0) {
            $ledgers = DB::table('account_ledgers')
                ->join('accounts', 'accounts.id', '=', 'account_ledgers.account_id')
                ->where([
                    'account_ledgers.foreign_key' => $id,
                    'account_ledgers.identifier' => 402
                ])
                ->select('account_ledgers.amount', 'accounts.name')
                ->get();
        }
        return view('admin.billing.detail', compact('bill', 'ledgers'));
    }
}
