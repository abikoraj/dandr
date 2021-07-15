<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Milkdata;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class MilkController extends Controller
{
    public function index(){
        return view('admin.milk.index');
    }

    public function saveMilkData(Request $request,$type){
        // dd($request->all());
        $actiontype=0;
        $date = str_replace('-', '', $request->date);
        $user = User::join('farmers','users.id','=','farmers.user_id')->where('users.no',$request->user_id)->where('farmers.center_id',$request->center_id)->select('users.*','farmers.center_id')->first();
        // $user=User::where('no',$request->user_id)->first();
        // dd($user,$request);
        if($user==null ){
            return response("Farmer Not Found",400);
        }else{
            if($user->no==null){
            return response("Farmer Not Found",500);

            }
        }

        $milkData=Milkdata::where('user_id',$user->id)->where('date',$date)->first();
        if($milkData==null){
            $milkData = new Milkdata();
            $milkData->date = $date;
            $milkData->user_id = $user->id;
            $milkData->center_id = $request->center_id;
            $actiontype=1;
        }

        //request->type 1=save/replace type=2 add
        $product = Product::where('id',env('milk_id'))->first();
        $oldmilk = 0;
        if($request->session == 0){
            if($type==0){
                $oldmilk=$milkData->m_amount;
                $milkData->m_amount = $request->milk_amount;

            }else{
                $milkData->m_amount += $request->milk_amount;

            }
        }else{
            if($type==0){
                $oldmilk=$milkData->e_amount;
                $milkData->e_amount = $request->milk_amount;
            }else{
                $milkData->e_amount += $request->milk_amount;
            }
        }
        if($product != null){
            if($type==0){
                $product->stock -= $oldmilk;
            }
            $product->stock += $request->milk_amount;
            $product->save();
        }
        $milkData->save();
        $milkData->no=$user->no;
        if($actiontype==1){
            return view('admin.milk.single',['d'=>$milkData]);
        }else{
            return response()->json($milkData->toArray());
        }
    }

    public function milkDataLoad(Request $request){
        $date = str_replace('-', '', $request->date);
        $milkData = Milkdata::where(['date'=>$date,'center_id'=>$request->center_id])->get();
        return view('admin.milk.dataload',['milkdatas'=>$milkData]);
    }

    public function loadFarmerData(Request $request){
        $farmers = User::join('farmers','farmers.user_id','=','users.id')->where('farmers.center_id',$request->center)->where('users.role',1)->select('users.*','farmers.center_id')->orderBy('users.no')->get();
        return view('admin.farmer.minlist',compact('farmers'));
    }

    public function update(Request $request){
        $milkdata=Milkdata::find($request->id);
        $milkdata->e_amount=$request->evening;
        $milkdata->m_amount=$request->morning;
        $milkdata->save();
        return response('ok',200);
    }
    public function delete(Request $request){
        $milkdata=Milkdata::find($request->id);
        $milkdata->delete();
        return response('ok',200);
    }

}
