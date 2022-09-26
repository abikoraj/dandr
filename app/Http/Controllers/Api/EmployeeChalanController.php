<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChalanPayment;
use App\Models\ChalanSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeChalanController extends Controller
{
    public function uploadChalanSales(Request $request)
    {
        $user = DB::table('users')->where('phone', $request->phone)->first(['id', 'name']);
        $chalan=DB::table('employee_chalans')
        ->where('date',$request->date)
        ->where('user_id',$request->user_id)->first(['id']);
        $chalanItem=DB::table('chalan_items')->where('employee_chalan_id',$chalan->id)
        ->where('item_id',$request->item_id)->first(['id']);
        $sellItem=new ChalanSale();
        $sellItem->item_id=$request->item_id;
        $sellItem->date=$request->date;
        $sellItem->qty=$request->qty;
        $sellItem->rate=$request->rate;
        $sellItem->total=$request->rate*$request->qty;
        $sellItem->due=$sellItem->total;
        $sellItem->paid=0;


        $sellItem->user_id=$user->id;
        $sellItem->chalan_item_id=$chalanItem->id;
        $sellItem->employee_chalan_id=$chalan->id;
        $sellItem->save();

        return response()->json(['status'=>true]);
    }

     public function uploadChalanPayment(Request $request)
    {
        $user = DB::table('users')->where('phone', $request->phone)->first(['id', 'name']);
        $chalan=DB::table('employee_chalans')
        ->where('date',$request->date)
        ->where('user_id',$request->user_id)->first(['id']);

        $chalanPayment=new ChalanPayment();
        $chalanPayment->amount=$request->amount;
        $chalanPayment->user_id=$user->id;
        $chalanPayment->employee_chalan_id=$chalan->id;
        $chalanPayment->save();

        return response()->json(['status'=>true]);
    }
}
