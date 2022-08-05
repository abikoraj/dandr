<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleManufactureController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            return view('admin.simplemanufacture.index');
        }
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="POST"){
            $data=($request->all());
            foreach ($data['rawMaterials'] as $key => $_value) {
                $value=(object)$_value;
                dd($value);
            }
        }else{
            $items=DB::table('items')->get(['id','title']);
            $centers=DB::table('centers')->get(['id','name']);
            return view('admin.simplemanufacture.add',compact('items','centers'));
        }
    }
}
