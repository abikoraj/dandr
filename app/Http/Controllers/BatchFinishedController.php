<?php

namespace App\Http\Controllers;

use App\Models\BatchFinished;
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

            $manufactured_items = DB::table('simple_manufacturing_items')->where('item_id',$request->item_id)->where('type',2)->get(['batch_no', 'id']);
            if ($request->multiple == 1) {
                $finishedBatchGroup=[];
                foreach ($finishedBatches->whereNotNull('to_batch_id') as $key => $finishedBatch) {
                    array_push($finishedBatchGroup,"'{$finishedBatch->batch_id}|{$finishedBatch->to_batch_id}'");
                }
                $query='';
                if(count($finishedBatchGroup)>0){
                    $query="and concat(batch_id,'|',to_batch_id) not in (".implode(',',$finishedBatchGroup).')';
                }
                // dd("select distinct(concat(batch_id,'|',to_batch_id)) as combo from bill_items where to_batch_id is not null and target_item_id=? {$query} ");
                $bill_items = DB::select("select distinct(concat(batch_id,'|',to_batch_id)) as combo from bill_items where to_batch_id is not null and target_item_id=? {$query} ", [$request->item_id]);
                // dd($bill_items);
                foreach ($bill_items as $key => $bill_item) {
                    $bill_item->batches=explode('|',$bill_item->combo);
                    $bill_item->startbatch=$manufactured_items->where('id',$bill_item->batches[0])->first()->batch_no;
                    $bill_item->endbatch=$manufactured_items->where('id',$bill_item->batches[1])->first()->batch_no;
                    $bill_item->batch=$bill_item->startbatch.' - '. $bill_item->endbatch;
                }
                return view('admin.item.finishbatch.datamultiple', compact('finishedBatches', 'manufactured_items','bill_items'));
                
            } else {
                return view('admin.item.finishbatch.data', compact('finishedBatches', 'manufactured_items'));
            }
        } else {


            $items = DB::table('items')->where('id', '<>', env('milk_id', -1))->whereIn('id', ConnectedItem::pluck('target_item_id'))->get(['id', 'title']);
            return view('admin.item.finishbatch.index', compact('items'));
        }
    }

    public function info(Request $request)
    {
        if ($request->type == 'single') {
            $batches = SimpleManufacturingItem::where('id', $request->batch_id)->get();
            $bill_items_query = BillItem::whereIn('batch_id', $batches->pluck('id'))->whereNull('to_batch_id');
        } else {
            $batches = SimpleManufacturingItem::where('id', '>=', $request->batch_id)
                ->where('id', '<=', $request->to_batch_id)
                ->where('type', 2)
                ->where('item_id', $request->item_id)->get();
            $bill_items_query = BillItem::where('batch_id', $request->batch_id)->where('to_batch_id', $request->to_batch_id);
        }
        $batch_id=$request->batch_id;
        $to_batch_id=$request->to_batch_id;
        $item_id=$request->item_id;
        $multi=$request->type == 'single'?0:1;
        $bill_items = $bill_items_query->get();
        if($bill_items->count()>0){

            return view('admin.item.finishbatch.info', compact('batches', 'bill_items','batch_id','to_batch_id','item_id','multi'));
        }else{
            return response('No selling batch remaning to close');
        }
    }

    public function add(Request $request)
    {
        $finishedBatch=new BatchFinished();
        $finishedBatch->batch_id=$request->batch_id;
        $finishedBatch->to_batch_id=$request->to_batch_id;
        $finishedBatch->item_id=$request->item_id;
        $finishedBatch->multi=$request->multi;
        $finishedBatch->fresh_qty=$request->fresh_qty;
        $finishedBatch->sold_qty=$request->sold_qty;
        $finishedBatch->loss_qty=$finishedBatch->fresh_qty-$request->sold_qty;
        $finishedBatch->save();
        maintainStock($request->item_id,$request->fresh_qty,env('maincenter'),'out');
        
    }


}
