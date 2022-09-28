<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChalanDueController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $dues=DB::select('select c.*,u.name,u.phone from 
            (select chalan_dues.*,ifnull((select sum(amount) from chalandue_payments where chalan_due_id=chalan_dues.id),0) as paid from chalan_dues where employee_id=? ) c
            join users u on u.id=c.user_id
            where c.amount>c.paid',[$request->id]);
            return view('admin.chalan.dues.data',compact('dues'));
            // throw new \Exception('Data not managed',404);
        }else{
            $employees=DB::select('select u.id,u.name from users u join employees e on u.id=e.user_id');
            // dd($employees);
            return view('admin.chalan.dues.index',compact('employees'));
        }
    }
}
