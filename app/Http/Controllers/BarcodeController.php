<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BarcodeController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $user=Auth::user();
            if(Hash::check($request->password,$user->password)){
                $barcode=new Barcode();
                $time=time();
                $barcode->pin=$request->pin;
                $barcode->token=bcrypt($time);
                $barcode->validtill=$time+300;
                $barcode->save();
                $data=(object)[
                    'url'=>url('/api').'/',
                    'token'=>$barcode->token
                ];
                return response()->json([
                    'data'=> json_encode($data),
                    'status'=>true
                ]);

            }else{
                return response()->json(['status'=>false,'message'=>"Wrong Password"]);
            }
        
        }else{
            return view('admin.barcode.index');
        }
    }

    public function setup(Request $request)
    {
        $now=time();
        $barcode=Barcode::where('token',$request->token)->first();
        if($barcode==null){
            return response()->json(['status'=>false,'message'=>"Invalid Token"]);
        }

        if($now<$barcode->validtill){
            if($request->pin==$barcode->pin){
                return response()->json(['status'=>true]);
            }else{
                return response()->json(['status'=>false,'message'=>'Wrong pin adress']);

            }

        }else{
            return response()->json(['status'=>false,'message'=>'Token expired, Please create new token in dashboard','data'=>[$now,$barcode->token]]);

        }

    }
}
