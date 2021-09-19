<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Bank;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Payment;
use App\Models\PosBill;
use App\Models\PosBillItem;
use App\Models\PosSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function add(Request $request)
    {
        // dd($request->all());
        $cid = session('counter');
        $setting = PosSetting::first();
        if ($setting == null) {
            return response("Day Not Opened, Please Contact Administrator.", 500);
        }else{
            if($setting->open!=1){
                return response("Day Not Opened, Please Contact Administrator.", 500);
            }
        }
        $counter = Counter::find($cid);
        $status=$counter->currentStatus();
        if($status->status!=2){
            response('Cannot Save Bill, Counter Not Running',500);
        }
        $fy = $setting->fiscalYear();
        if ($fy->null) {
            return response("No Fiscal Year Set For The Date, Please Check Date.", 500);
        }
        $user = Auth::user();

        $bill = new PosBill();
        $data = PosBill::where('fiscal_year_id', $fy->id)->select(DB::raw('max(cast(bill_no as int)) as max'))->first();
        // dd($data);
        $bno = 0;
        if ($data != null) {
            $bno = $data->max;
        }
        $bno += 1;

        $bill->bill_no = $bno;
        $bill->date = $setting->date;
        $bill->counter_id = $cid;
        $bill->counter_name = $counter->name;
        $bill->fiscal_year_id = $fy->id;
        
        if ($request->filled('customer')) {
            $bill->customer_name = $request->customer['name'];
            $bill->customer_address = $request->customer['address'];
            $bill->customer_phone = $request->customer['phone'];
            $bill->customer_pan = $request->panvat;
            $bill->customer_id = $request->customer['id'];
        } else {
            $bill->customer_name = "Walking Customer";
        }
        $bill->total = $request->total['total'];
        $bill->discount = $request->total['discount'];
        $bill->taxable = $request->total['taxable'];
        $bill->tax = $request->total['tax'];
        $bill->rounding = $request->total['rounding'];
        $bill->grandtotal = $request->total['grandtotal'];
        $bill->paid = $request->total['paid'];
        $bill->due = $request->total['due'];
        $bill->return = $request->total['return'];
        $bill->user_id = $user->id;
        $bill->save();
        $bis = [];
        foreach ($request->billitems as $key => $_bi) {

            if ($_bi != null) {
                $bi = new PosBillItem();
                $bi->pos_bill_id = $bill->id;
                $bi->qty = $_bi['qty'];
                $item = Item::where('id', $_bi['item_id'])->select('id', 'title', 'sell_price', 'stock', 'trackstock')->first();
                $bi->rate = $_bi['item_rate'];
                $bi->name = $_bi['item_name'];
                $bi->item_id = $_bi['item_id'];
                $bi->amount = $_bi['amount'];
                $bi->discount = $_bi['discount'];
                $bi->taxable = $_bi['taxable'];
                $bi->tax = $_bi['tax'];
                $bi->total = $_bi['total'];
                $bi->use_tax=$_bi['item_taxable'];
                if ($item->trackstock == 1) {
                    $item->stock -= $bi->qty;
                    $item->save();
                }
                $bi->save();
                array_push($bis, $bi);
            }
        }
        if (env('savePayment', 0) == 1) {
            $payment = new Payment();
            $payment->type = $request->payment_type;
            if ($payment->type == 1) {
                $payment->bank_id = $request->bank;
                $payment->bank_name = Bank::find($payment->bank_id)->name;
                $payment->cardno = $request->cardno;
                $payment->txn_no = $request->txnno;
            } elseif ($payment->type == 2) {
                $payment->bank_name = $request->bank_name;
                $payment->chequeno = $request->chequeno;
            } elseif ($payment->type == 3) {
                $payment->payment_gateway_id = $request->gateway;
                $request->txn_no = $request->txnno;
            }
            $payment->foreign_key = $bill->id;
            $payment->save();
        }

        $status->current+=($bill->paid-$bill->return);
        $status->save();
        if ($request->filled('customer')) {
            $user_id=Customer::where('id',$bill->customer_id )->select('user_id')->first()->user_id;
            $ledger = new LedgerManage($user_id);
            $paidamount=($bill->grandtotal<$bill->paid)?$bill->grandtotal:$bill->paid;
            
            if(env('acc_system','old')=='old'){
                $ledger->addLedger('Bill No '.$bill->bill_no, 1,    $bill->grandtotal,  $bill->date, 130, $bill->id);
                if ($paidamount > 0) {
                    $ledger->addLedger('Payment For'.$bill->bill_no, 2, $paidamount,  $bill->date, 131, $bill->id);
                }
            }else{
                $ledger->addLedger('Bill No '.$bill->bill_no, 2,    $bill->grandtotal,  $bill->date, 130, $bill->id);
                if ($paidamount > 0) {
                    $ledger->addLedger('Payment For'.$bill->bill_no, 1, $paidamount,  $bill->date, 131, $bill->id);
                }
            }
        }

        $b = PosBill::find($bill->id);
        $b->billitems;
        $b->payment;
        $b->user = $user;
        return response()->json($b);
    }

    public function print(PosBill $bill)
    {
        $bill->billitems;
        $bill->payment;
        $user = Auth::user();
        $bill->printed_by = $user->name;
        $bill->copy += 1;
        $bill->save();
        $bill->user = $user;
        return view('admin.print.bill', compact('bill'));
    }
    public function printed(Request $request)
    {
        $bill = PosBill::find($request->id);
        $bill->printed_by = Auth::user()->name;
        $bill->copy += 1;
        $bill->save();
    }
}
