<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function table(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            $items=DB::select('select id,title,sell_price,number as rate from items where posonly=1');
            $tables=DB::table('tables')->get();
            $sections=DB::table('sections')->get();
            return view('restaurant.table.index',compact('tables','sections','items'));
        }
    }
}
