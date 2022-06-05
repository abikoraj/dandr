<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\Milkdata;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $user = User::join('farmers','users.id','=','farmers.user_id')->where('farmers.no',$request->user_id)->where('farmers.center_id',$request->center_id)->select('users.id','farmers.no','farmers.center_id')->first();
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
        $product = Item::where('id',env('milk_id'))->first();
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
            if(env('multi_stock')){

                $centerStock=$product->stock($request->center_id);
                $new=false;
                if($centerStock==null){
                    $centerStock=new CenterStock();
                    $centerStock->item_id=$product->id;
                    $centerStock->center_id=$request->center_id;
                    $centerStock->amount=0;
                    $centerStock->save();
                    $new=true;
                }
            }
            if($type==0){
                $product->stock -= $oldmilk;
                if(!$new && env('multi_stock')){
                    $centerStock->amount-=$oldmilk;
                }
            }
            $product->stock += $request->milk_amount;
            if(env('multi_stock')){
                $centerStock->amount += $request->milk_amount;
                $centerStock->save();
            }
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
        $milkData = DB::table('milkdatas')
        ->join('farmers','farmers.user_id','=','milkdatas.user_id')
        ->where(['date'=>$date,'milkdatas.center_id'=>$request->center_id])
        ->select('milkdatas.*','farmers.no')
        ->get();
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
        $product = Item::where('id',env('milk_id'))->first();
        if($product!=null){
            $product->stock-= ($milkdata->e_amount+$milkdata->m_amount);
            $product->save();
        }
        $milkdata->delete();
        return response('ok',200);
    }

}
