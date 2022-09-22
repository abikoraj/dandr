<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BatchFinishedController extends Controller
{
    public function index(Request $request)
    {

        if($request->method()=="POST"){
            $finishedBatches=DB::table('batch_finisheds')
            ->where('item_id',$request->item_id)
            ->get();
            $manufactured_items=DB::table('simple_manufacturing_items')->whereIn('id',$finishedBatches->pluck('batch_id'))->get(['batch_no','id']);
            return view('admin.item.finishbatch.data',compact('finishedBatches','manufactured_items'));
        }else{

            $batchFinishers=explode(",", env('batch_finishers',''));
            if(count($batchFinishers)>0){
                $items=DB::table('items')->whereIn('id',$batchFinishers)->get(['id','title']);
                return view('admin.item.finishbatch.index',compact('items'));
            }else{  
                return abort(404);
            }
        }
    }
}
