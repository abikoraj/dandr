<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Snffat;
use App\Models\User;
use Illuminate\Http\Request;

class SnffatController extends Controller
{
    public function index(){
        return view('admin.snf.index');
    }

    public function saveSnffatData(Request $request){
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
        // $checkData = Snffat::where(['date'=>$date,'user_id'=>$user->id,'center_id'=>$request->center_id])->first();
        // if($checkData == null){
            $snffat = new Snffat();
            $snffat->snf = $request->snf;
            $snffat->fat = $request->fat;
            $snffat->date = $date;
            $snffat->user_id = $user->id;
            $snffat->center_id = $request->center_id;
            $snffat->save();
            $snffat->no=$request->user_id;
            return view('admin.snf.single',compact('snffat'));
        // }else{
        //     $checkData->snf = $request->snf;
        //     $checkData->fat = $request->fat;
        //     $checkData->save();
        //     return response()->json($checkData);
        // }

    }


    public function snffatDataLoad(Request $request){
        $date = str_replace('-', '', $request->date);
        $data = Snffat::where(['date'=>$date ,'center_id'=>$request->center_id])->get();
        return view('admin.snf.dataload',['data'=>$data]);
    }

    public function delete(Request $request){
        // dd($request->all());
        $snffat=Snffat::find($request->id);
        $snffat->delete();
        return response('ok',200);
    }

    public function update(Request $request){
        $snffat=Snffat::find($request->id);
        $snffat->snf = $request->snf;
        $snffat->fat = $request->fat;
        $snffat->save();
        return response('ok',200);
    }

}
