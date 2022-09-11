<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\paymentSave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // public function index($id,$identifire)
    // {

    //     $payment=paymentSave::where('foreign_id',$id)->where('identifire',$identifire)->first();
    //     if($payment!=null){
    //         $method=$payment->method;
    //         $detail=json_decode($payment->detail);
    //         if($payment->method==3){
    //             $banks=[];
    //             foreach ($detail->b as $key => $bdetail) {
    //                 array_push($banks,[
    //                     'detail'=>DB::table('banks')->where('id',$bdetail[0])->first(['id','name']),
    //                     'amount'=>$bdetail[1]
    //                 ]);
    //             }
    //             return view('admin.payment.edit',compact('banks','detail','method'));
    //         }else if($payment->method==1){
    //             return response("Paid Via Cash");

    //         }else if($payment->method==2){
    //             return response("Paid Via Bank - ".DB::table('banks')->where('id',$detail[0])->get['name']->name);
    //         }
    //     }
    // }

    public function index($id,$identifire)
    {
        $ledgers=DB::table('account_ledgers')
        ->join('accounts','accounts.id','=','account_ledgers.account_id')
        ->where('account_ledgers.identifier',$identifire)->where('foreign_key',$id)->get(['account_ledgers.id','account_ledgers.amount','accounts.name']);
        return view('admin.payment.edit',compact('ledgers'));

    }
}
