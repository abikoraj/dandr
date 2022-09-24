<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConnectedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheeseController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $connectedItem = ConnectedItem::where('item_id',$request->item_id)->where('target_item_id',$request->target_item_id)->first();
            if($connectedItem!=null){
                throw new \Exception('Item Already Managed');
            }
            if($request->target_item_id==$request->item_id){
                throw new \Exception('Same item selected');
            }
            $connectedItem =new ConnectedItem();
            $connectedItem->item_id=$request->item_id;
            $connectedItem->target_item_id=$request->target_item_id;
            $connectedItem->save();
            return $connectedItem;

        }else{
            $connectedItems=DB::table('connected_items')->get();
            $items=DB::table('items')->get(['id','title']);
            $manufacturedItems=DB::table('items')->whereIn('id',DB::table('manufactured_products')->pluck('item_id'))->get(['id','title']);
            return view('admin.manufacture.connect.index',compact('connectedItems','manufacturedItems','items'));
        }
    }

    public function del(Request $request){
        DB::table('connected_items')->where('id',$request->id)->delete();
    }
}
