<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChalanPayment;
use App\Models\ChalanSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeChalanController extends Controller
{
    public function uploadChalanSales(Request $request)
    {
        
        $chalan=DB::table('employee_chalans')
        ->where('date',$request->date)
        ->where('user_id',Auth::user()->id)->first(['id','closed']);

        if($chalan->approved==0){
            return response()->json([
                'status'=>false,
                'message'=>'Chalan not approved'
            ]);
        }
        if($chalan->closed==1){
            return response()->json([
                'status'=>false,
                'message'=>'Chalan is already closed'
            ]);
        }
        $user = getCustomer($request->phone,$request->name);
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
        $chalan=DB::table('employee_chalans')
        ->where('date',$request->date)
        ->where('user_id',Auth::user()->id)->first(['id','closed']);
        if($chalan->closed==1){
            return response()->json([
                'status'=>false,
                'message'=>'Chalan Is already closed'
            ]);
        }

        if($chalan->approved==0){
            return response()->json([
                'status'=>false,
                'message'=>'Chalan not approved'
            ]);
        }
        $user = getCustomer($request->phone,$request->name);
        $chalanPayment=new ChalanPayment();
        $chalanPayment->amount=$request->amount;
        $chalanPayment->user_id=$user->id;
        $chalanPayment->employee_chalan_id=$chalan->id;
        $chalanPayment->save();

        return response()->json(['status'=>true]);
    }


}
