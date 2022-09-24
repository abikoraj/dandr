<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use App\Models\ConnectedItem;
use App\Models\SimpleManufacturingItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchFinishedController extends Controller
{
    public function index(Request $request)
    {

        if ($request->method() == "POST") {
            $finishedBatches = DB::table('batch_finisheds')
                ->where('item_id', $request->item_id)
                ->get();
            $manufactured_items = DB::table('simple_manufacturing_items')->whereNotIn('id', $finishedBatches->pluck('batch_id'))->get(['batch_no', 'id']);
            return view('admin.item.finishbatch.data', compact('finishedBatches', 'manufactured_items'));
        } else {

            
            $items = DB::table('items')->where('id','<>',env('milk_id',-1))->whereIn('id',ConnectedItem::pluck('target_item_id'))->get(['id', 'title']);
            return view('admin.item.finishbatch.index', compact('items'));
            
        }
    }

    public function info(Request $request)
    {
        if($request->type=='single'){
            $batches = SimpleManufacturingItem::where('id',$request->batch_id)->get();
            $bill_items_query=BillItem::whereIn('batch_id',$batches->pluck('id'))->whereNull('to_batch_id');
        }else{
            $batches = SimpleManufacturingItem::where('id', '>=',$request->batch_id)
            ->where('id', '<=',$request->to_batch_id)
            ->where('type',2)
            ->where('item_id',$request->item_id)->get();
            $bill_items_query=BillItem::where('batch_id',$request->batch_id)->where('to_batch_id',$request->to_batch_id);
        }
        // $bill_items=$bill_items_query->select("
        //     select 
        // ")
        return view('admin.item.finishbatch.info',compact('batches','bill_items'));
       
    }
}
