<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Distributer;
use App\Models\DistributorPayment;
use App\Models\Distributorsell;
use App\Models\Ledger;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributorPaymentController extends Controller
{
    public function index(){
        return view('admin.distributer.payment.index');
    }

    public function due(Request $request){
        // $bills=Distributorsell::where('distributer_id',$request->id)->where('deu','>',0)->get();
        $distributor=Distributer::find($request->id);
        $id=$request->id;
        $distributor->balance=Ledger::where('user_id',$distributor->user_id)->where('type',2)->sum('amount') - Ledger::where('user_id',$distributor->user_id)->where('type',1)->sum('amount');
        $distributor->payments=DB::table('distributor_payments')->where('user_id',$distributor->user_id)->get();

        return view('admin.distributer.payment.data',compact('distributor','id'));
    }

    public function del($id){
        DB::table('distributor_payments')->where('id',$id)->delete();
        DB::table('ledgers')->where('foreign_key',$id)->where('identifire',150)->delete();
        PaymentManager::remove($id,150);
        return response('ok');
    }

    public function pay(Request $request){
        // $bills1=Distributorsell::where('distributer_id',$request->id)->where('deu','>',0)->get();
        $distributor=Distributer::find($request->id);
        $date = str_replace('-','',$request->date);
        // dd($request,$bills1);
        $amount =$request->amount;


        // foreach($bills1 as $bill){
        //     if($bill->deu>=$amount){
        //         $bill->paid=$amount;
        //         $bill->deu-=$amount;
        //         $bill->save();
        //         $amount=0;
        //     }else{
        //         $bill->paid=$bill->deu;

        //         $amount-=$bill->deu;
        //         $bill->deu=0;
        //         $bill->save();
        //     }

        //     if($amount<=0){
        //         break;
        //     }
        // }

        $payment=new DistributorPayment();
        // $paymentDatam
        $payment->amount=$request->amount;
        $payment->date=$date;
        $payment->payment_detail=$request->method??"";
        $payment->user_id=$distributor->user_id;
        $payment->save();

        $ledger=new LedgerManage($distributor->user_id);
        if(env('acc_system','old')=='old'){
            $ledger->addLedger("Payment by distributor",2,$request->amount,$date,'150',$payment->id);
        }else{
            $ledger->addLedger("Payment by distributor",1,$request->amount,$date,'150',$payment->id);
        }
        return response('ok');
        new PaymentManager($request,$payment->id,150);
        // // $bills=Distributorsell::where('distributer_id',$request->id)->where('deu','>',0)->get();
        // $id=$request->id;
        // $distributor->balance=Ledger::where('user_id',$distributor->user_id)->where('type',2)->sum('amount') - Ledger::where('user_id',$distributor->user_id)->where('type',1)->sum('amount');
        // return view('admin.distributer.payment.data',compact('distributor','id'));

    }
}
