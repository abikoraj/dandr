<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChalanPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChalanPaymentController extends Controller
{
    public function index(Request $request){
        // $customers = DB::table('users')
        // ->join('customers','users.id','customers.user_id')->select('users.name','users.id')->get()->toArray();
        // $distributors = DB::table('users')
        // ->join('distributers','users.id','distributers.user_id')->select('users.name','users.id')->get()->toArray();
        // $users = array_merge($customers,$distributors);
        // return view('admin.chalan.payment.index',compact('users'));
        $payments=DB::select('select c.*,u.name from chalan_payments c join users u on c.user_id=u.id where employee_chalan_id=?', [$request->employee_chalan_id]);

        return view('admin.chalan.payment.index',compact('payments'));
    }

    public function delPayment(Request $request)
    {
        DB::delete('delete from chalan_payments where id=?',[$request->id]);

    }

    public function addPayment(Request $request){
        // dd($request->all());
        $payment = new ChalanPayment();
        $payment->employee_chalan_id = $request->employee_chalan_id;
        $payment->user_id = $request->user_id;
        $payment->amount = $request->amount;
        $payment->save();
        $user = DB::table('users')->where('id',$request->user_id)->select('name')->first();
        $payment->name=$user->name;
        // dd($payment);
        return view('admin.chalan.payment.single',compact('payment'));
    }
}
