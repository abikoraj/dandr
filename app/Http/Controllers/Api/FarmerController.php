<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Milkdata;
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
        }
        return response()->json(['staus'=>true]);
    }
}
