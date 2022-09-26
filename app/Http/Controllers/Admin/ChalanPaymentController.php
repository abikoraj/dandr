<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChalanPayment;
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
        DB::delete('delete from chalan_payments  where id=?',[$request->id]);

    }

    public function addPayment(Request $request){
        $date = getNepaliDate($request->date);
        $pay = new ChalanPayment();
        $pay->date = $date;
        $pay->employee_chalan_id = $request->employee_chalan_id;
        $pay->user_id = $request->user_id;
        $pay->save();
        return response([
            // 'data' = $pay
        ]);
    }
}
