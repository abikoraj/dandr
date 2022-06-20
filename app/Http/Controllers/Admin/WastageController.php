<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WastageController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            $centers=DB::select('select id,name  from centers');
            $items=DB::select('select id,title  from items');
            return view('admin.wastage.index',compact('centers','items'));
        }
    }

    public function add(Request $request)
    {
        dd($request->all());
    }
}
