<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Milkdata;
use App\Models\Snffat;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerController extends Controller
{
    public function centers(){
        return  response()->json(DB::select('select * from centers'));
    }
    public function list( )
    {
        return response()->json(DB::select('select u.id,u.name,u.no,f.center_id from farmers f join users u on f.user_id=u.id '));
    }

    public function pullMilkData(Request $request){
        if(!$request->filled('date')){
            throw new Exception('Date is needed');
        }
        $date=(int)str_replace("-",'',$request->date);

        $query=DB::table('milkdatas')->where('date',$date);

        if($request->filled('center_id')){
            $query=$query->where('center_id',$request->center_id);
        }
        return response(json_encode($query->get(),JSON_NUMERIC_CHECK));
    }

    public function pushMilkData(Request $request){
        
        $date=(int)str_replace("-",'',$request->date);

        $center_id=$request->center_id;
        $localDatas=Milkdata::where('center_id',$center_id)->where('date',$date)->get();
        $now=Carbon::now()->toDateTimeString();
        $oldAmount=$localDatas->sum('m_amount')+$localDatas->sum('e_amount');
        $newAmount=0;
        foreach ($request->data as $_data) {
            $data=(object)$_data;
            $localData=$localDatas->where('user_id',$data->id)->first();
            if($localData!=null){
                $change=false;
                if($data->m_amount!=$localData->m_amount){
                    $localData->m_amount=$data->m_amount;
                    $change=true;
                }
                if($data->e_amount!=$localData->e_amount){
                    $localData->e_amount=$data->e_amount;
                    $change=true;
                }
                if ($change) {
                    $localData->save();
                }

            }else{
                MilkData::insert([
                    "user_id"=>$data->id,
                    "m_amount"=>$data->m_amount,
                    "e_amount"=>$data->e_amount,
                    "date"=>$date,
                    "center_id"=>$center_id,
                    "created_at"=>$now,
                    "updated_at"=>$now,
                ]);
            }
            $newAmount+=$data->m_amount+$data->e_amount;

        }

        $amount=$oldAmount-$newAmount;
        if($amount!=0){
            $extracenters = explode(",", env('extracenter', ''));
            $milk_id=env('milk_id',-1);
            if($milk_id>0 && !in_array($center_id,$extracenters) && $amount!=0){
                maintainStock($milk_id,($amount<0?(-1*$amount):$amount),$center_id,($amount<0?'in':'out'));
            }
        }
        
        return response()->json(['staus'=>true]);
    }

    public function pushFatSnf(Request $request)
    {
        $date=(int)str_replace("-",'',$request->date);
        $center_id=$request->center_id;
        $localDatas=Snffat::where('center_id',$center_id)->where('date',$date)->get()->groupBy('user_id');
        $datas=collect($request->data)->groupBy('id');
        $now=Carbon::now();
        foreach ($datas as $key=>$_datas) {
            if(isset($localDatas[$key])){
                $_localDatas=$localDatas[$key];
                $i=0;
                $inLength=count($_datas);
                
                foreach ($_localDatas as $key => $localData) {
                    $data=(object)$_datas[$i];
                    $localData->snf=$data->snf;
                    $localData->fat=$data->fat;
                    $localData->save();
                    if($inLength==$i){
                        break;
                    }
                    $i+=1;
                }
               
                for ($j=$i; $j < $inLength; $j++) { 
                    $data=(object)$_datas[$j];
                    Snffat::insert([
                        "user_id"=>$data->id,
                        "snf"=>$data->snf,
                        "fat"=>$data->fat,
                        "date"=>$date,
                        "center_id"=>$center_id,
                        "created_at"=>$now,
                        "updated_at"=>$now,
                    ]);
                }

             
            }else{
                foreach ($_datas as $key1 => $_data) {
                    $data=(object)$_data;
                    Snffat::insert([
                        "user_id"=>$data->id,
                        "snf"=>$data->snf,
                        "fat"=>$data->fat,
                        "date"=>$date,
                        "center_id"=>$center_id,
                        "created_at"=>$now,
                        "updated_at"=>$now,
                    ]);
                }
            }
            // return response()->json([$_data,$_localDatas]);
        }

    }
}
