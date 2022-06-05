<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PackagingController extends Controller
{
    //
    public function index(Request $request )
    {
        if($request->getMethod()=="POST"){

        }else{
            return view('admin.package.index');
        }
    }

    public function add(Request $request){
        if($request->getMethod()){

        }else{
            return view('');
        }
    }
}
