<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\FarmerReport;
use App\Models\Ledger;
use App\Models\MilkPayment;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;

class MilkPaymentController extends Controller
{
    public function index(Request $request){
        if($request->getMethod()=="POST"){
            $payments=MilkPayment::join('users','users.id','milk_payments.user_id')->where(
                [
                    'milk_payments.session'=>$request->session,
                    'milk_payments.center_id'=>$request->center_id,
                    'milk_payments.year'=>$request->year,
                    'milk_payments.month'=>$request->month,
                ])->select('milk_payments.*','users.no','users.name')->get();
            // dd($payments);
            return view('admin.milk.payment.list',compact('payments'));
        }else{
            return view('admin.milk.payment.index');
        }
    }

    public function add(Request $request){

        $date = str_replace('-', '', $request->date);
        $np=new NepaliDate((int)$date);
        $user = User::join('farmers','users.id','=','farmers.user_id')->where('users.no',$request->no)->where('farmers.center_id',$request->center_id)->select('users.name','users.id','users.no','farmers.center_id')->first();
        $sessionChecked = FarmerReport::where(['user_id'=>$user->id,'year'=>$request->year,'month'=>$request->month,'session'=>$request->session])->count();
        if($sessionChecked>0 && $np->isPrevClosed($user->id)){
            $payment=new MilkPayment();
            $payment->session=$request->session;
            $payment->year=$request->year;
            $payment->month=$request->month;
            $payment->center_id=$request->center_id;
            $payment->amount=$request->amount;
            $payment->user_id=$user->id;
            $payment->date=$date;
            $payment->save();
            $payment->name=$user->name;
            $payment->no=$user->no;
            $ledger=new LedgerManage($user->id);
            $ledger->addLedger('Payment Milk Payment Given To Farmer',1,$request->amount,$date,'121',$payment->id);
            return view('admin.milk.payment.single',compact('payment'));
        }else{
            return '<tr class="text-center"><td colspan="4"> <strong> <span class="text-danger">Your payment has been failed due to Session is not closed yet !</span></strong></td></tr>';
        }
    }

    public function update(Request $request){
        // dd($request->all());
        $date = str_replace('-', '', $request->date);
        $payment=MilkPayment::find($request->id);
        $payment->date=$date;
        $payment->amount=$request->amount;
        $payment->save();
        $l=Ledger::where(
            [
                ['identifire','=',121],
                ['foreign_key','=',$request->id]
            ]
        )->first();
        $l->amount=$request->amount;
        $l->date=$date;
        $l->save();
        return response('ok');
    }

    public function delete(Request $request){
        // dd($request->all());
        $date = str_replace('-', '', $request->date);
        $payment=MilkPayment::find($request->id);
       
        $payment->delete();
        $l=Ledger::where(
            [
                ['identifire','=',121],
                ['foreign_key','=',$request->id]
            ]
        )->first();
     
        $l->delete();
        return response('ok');
    }
}
