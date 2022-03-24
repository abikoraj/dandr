<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemStockController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $items=DB::select('select title as t,minqty as m,
            (select concat(\'{"i":\',id,\',"a":\',amount,\',"r":\',rate,\',"w":\',wholesale,\'}\') from center_stocks where item_id=items.id and center_id=?) as c
            from items', [$request->center_id]);
            return response()->json($items);
        }else{
            $centers=DB::table('centers')->get(['id','name']);
            return view('admin.item.centerstock',compact('centers'));
        }
    }
}
