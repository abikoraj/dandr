<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Counter;
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
        $cid=session('counter');
        $setting = PosSetting::first();
        $counter=Counter::find($cid);
        if ($setting == null) {
            return response("Day Not Opened, Please Contact Administrator.", 500);
        }
        $fy = $setting->fiscalYear();
        if ($fy->null) {
            return response("No Fiscal Year Set For The Date, Please Check Date.", 500);
        }
        $user=Auth::user();

        $bill = new PosBill();
        $data = PosBill::where('fiscal_year_id', $fy->id)->select(DB::raw('max(cast(bill_no as int)) as max'))->first();
        // dd($data);
        $bno = 0;
        if ($data != null) {
            $bno=$data->max;
        }
        $bno += 1;

        $bill->bill_no = $bno;
        $bill->date = $setting->date;
        $bill->counter_id = $cid;
        $bill->counter_name = $counter->name;
        $bill->fiscal_year_id = $fy->id;
        if($request->filled('customer')){
            $bill->customer_name = $request->customer['name'];
            $bill->customer_address = $request->customer['address'];
            $bill->customer_phone = $request->customer['phone'];
            $bill->customer_pan = $request->panvat;
            $bill->customer_id = $request->customer['id'];
        }else{
            $bill->customer_name ="Walking Customer";

        }

        $bill->total = $request->total['total'];
        $bill->discount = $request->total['discount'];
        $bill->taxable = $request->total['taxable'];
        $bill->tax = $request->total['tax'];
        $bill->grandtotal = $request->total['grandtotal'];
        $bill->paid = $request->total['paid'];
        $bill->due = $request->total['due'];
        $bill->return = $request->total['return'];
        $bill->user_id=$user->id;
        $bill->save();
        $bis=[];
        foreach ($request->billitems as $key => $_bi) {
            if($_bi!=null){
                $bi=new PosBillItem();
                $bi->pos_bill_id=$bill->id;
                $bi->qty=$_bi['amount'];
                $item=Item::where('id',$_bi['item']['id'])->select('id','title','sell_price','stock','trackstock')->first();
                $bi->rate=$item->sell_price;
                $bi->name=$item->title;
                $bi->item_id=$item->id;
                $bi->total=$bi->qty*$bi->rate;
                if($item->trackstock==1){
                    $item->stock-=$bi->qty;
                    $item->save();
                }
                $bi->save();
                array_push($bis,$bi);
            }
        }
        if(env('savePayment',0)==1){
            $payment=new Payment();
            $payment->type=$request->payment_type;
            if($payment->type==1){
                $payment->bank_id=$request->bank;
                $payment->bank_name=Bank::find($payment->bank_id)->name;
                $payment->cardno=$request->cardno;
                $payment->txn_no=$request->txnno;
            }elseif($payment->type==2){
                $payment->bank_name=$request->bank_name;
                $payment->chequeno=$request->chequeno;

            }elseif($payment->type==3){
                $payment->payment_gateway_id=$request->gateway;
                $request->txn_no=$request->txnno;
            }
            $payment->foreign_key=$bill->id;
            $payment->save();
        }
        $b=PosBill::find($bill->id);
        $b->billitems;
        $b->payment;
        $b->user=$user;
        return response()->json($b);
    }

    public function print(PosBill $bill){
        $bill->billitems;
        $bill->payment;
        $bill->user=Auth::user();
        return view('admin.print.bill',compact('bill'));
    }
}
