<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class PointController extends Controller
{
    //
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            if($request->type==1){
                $request->validate([
                    'point'=>'numeric|not_in:0|min:0|required',
                    'per'=>'numeric|not_in:0|min:0|required',
                ]);
            }

            setSetting('point',[
                'type'=>$request->type,
                'point'=>$request->point??0,
                'per'=>$request->per??1
            ]);
            return redirect()->back();
        }else{
            $point=getSetting('point')??(object)([
                'type'=>0,
                'point'=>0,
                'per'=>0
            ]);
            return view('admin.point.index',compact('point'));
        }
    }
}
