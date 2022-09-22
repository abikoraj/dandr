<?php

namespace App\Http\Controllers;

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
            $manufactured_items = DB::table('simple_manufacturing_items')->whereIn('id', $finishedBatches->pluck('batch_id'))->get(['batch_no', 'id']);
            return view('admin.item.finishbatch.data', compact('finishedBatches', 'manufactured_items'));
        } else {

            $batchFinishers = explode(",", env('batch_finishers', ''));
            if (count($batchFinishers) > 0) {
                $items = DB::table('items')->whereIn('id', $batchFinishers)->get(['id', 'title']);
                return view('admin.item.finishbatch.index', compact('items'));
            } else {
                return abort(404);
            }
        }
    }

    public function info(Request $request)
    {
        $batch = SimpleManufacturingItem::where('id', $request->batch_id)->get();
        $batchFinished=
        $produced = $batch->amount;
        if ($request->mode == 'single') {
            
        } else {
            $tobatch = SimpleManufacturingItem::where('id', $request->to_batch_id)->get();
            
            if($batch->date>$tobatch->date){
                throw new \Exception('Newer Batch is selected in To Batch');
            }

        }
    }
}
