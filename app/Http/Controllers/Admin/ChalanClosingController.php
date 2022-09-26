<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeChalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChalanClosingController extends Controller
{
    const notes=[1000,500,100,50,25,20,10,5,2,1];

    public function index(Request $request,$id)
    {
        $chalan=EmployeeChalan::where('id',$id)->first();
        $payments=DB::table('chalan_payments')->where('employee_chalan_id',$id)->get();
        $chalanItems=DB::table('chalan_items')
        ->join('items','items.id','=','chalan_items.item_id')
        ->where('employee_chalan_id',$id)
        ->select('chalan_items.*','items.title','items.unit')
        ->get();
        $sellItems=DB::table('chalan_sales')
        ->join('items','items.id','=','chalan_sales.item_id')
        ->where('employee_chalan_id',$id)
        ->select('chalan_sales.*','items.title','items.unit')
        ->get();
        $user_ids=array_merge($payments->pluck('user_id')->toArray(),$sellItems->pluck('user_id')->toArray());

        $users=DB::table('users')->whereIn('id',$user_ids)->get(['id','name']);
        
        foreach ($users as $key => $user) {
            $user->sales=$sellItems->where('user_id',$user->id);
            $user->payments=$payments->where('user_id',$user->id);
            $user->sales_amount=$sellItems->where('user_id',$user->id)->sum('total');
            $user->payments_amount=$payments->where('user_id',$user->id)->sum('amount');
            $balance=$user->sales_amount-$user->payments_amount;
            $user->due=$balance>0?$balance:0;
            $user->balance=$balance<0?(-1*$balance):0;
        }
        
        foreach ($chalanItems as $key => $chalanItem) {
            $chalanItem->sold=$sellItems->where('item_id',$chalanItem->item_id)->sum('qty');
            $chalanItem->wastage=0;
            $chalanItem->remaning=$chalanItem->qty - $chalanItem->sold - $chalanItem->wastage;
        }
        $notes=self::notes;
        $banks=DB::table('banks')->get(['name','account_id']);
        
        if($request->getMethod()=="GET"){

            return view('admin.chalan.closing.index',compact('chalan','users','chalanItems','notes','banks'));
        }else{
            
        }
    }
}
