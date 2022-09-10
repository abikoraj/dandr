<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SimpleManufacturing;
use App\Models\SimpleManufacturingItem;
use App\NepaliDate;
use App\NepaliDateHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleManufactureController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){

            $processes_query=DB::table('simple_manufacturings')->where('canceled',0);
            $processes=rangeSelector($request,$processes_query)->get();
            if($processes->count()>0){
                $ids="(". implode(",",$processes->pluck('id')->toArray()??[]).")";
                $items=collect( DB::select("select mi.*,i.title from (select item_id,type,sum(amount) as amount from simple_manufacturing_items 
                where simple_manufacturing_id in {$ids}  group by item_id, type) mi join items i on i.id = mi.item_id"));
            }else{
                $items=collect([]);
            }
            // dd($processes,$items);
            return view('admin.simplemanufacture.data',compact('processes','items'));
        }else{
            return view('admin.simplemanufacture.index');
        }
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="POST"){
            $date=str_replace("-","",$request->date);
            // $item_ids="(".implode(",",$request->item_ids) . ")";
            $items=DB::table('items')->select(DB::raw(' id,cost_price,sell_price'))->whereIn('id',$request->item_ids)->get();
            $process=new SimpleManufacturing();
            $process->date=$date;
            $process->title='';
            $process->save();
            $titles=[];
            foreach ($request->items as $key => $_value) {
                $value=(object)$_value;
                $item=new SimpleManufacturingItem();
                $item->type=$value->type;
                $item->center_id=$value->center_id;
                $item->item_id=$value->item_id;
                $item->amount=$value->amount;
                $item->simple_manufacturing_id=$process->id;
                $localItem=$items->where('id',$item->item_id)->first();
                if($localItem!=null){
                    $item->rate=implode("|",[$localItem->cost_price,$localItem->sell_price]);
                }
                if($item->type==2){
                    $product=DB::table('manufactured_products')->where('item_id',$item->item_id)->first();
                    if($product!=null){
                        $d=NepaliDateHelper::withDate($date);
                        $item->expiry=$d->addDays($product->expairy_days);
                    }
                    maintainStock($item->item_id,$item->amount,$item->center_id,'in');
                    array_push($titles,"({$value->item_title} X {$item->amount})");
                
                }else{
                    maintainStock($item->item_id,$item->amount,$item->center_id,'out');
                }
                
                $item->save();
            }

            $process->title=implode(",",$titles);
            $process->save();
            return response($process);
        }else{
            $items=DB::table('items')->get(['id','title']);
            $centers=DB::table('centers')->get(['id','name']);
            return view('admin.simplemanufacture.add',compact('items','centers'));
        }
    }

    public function detail(SimpleManufacturing $process){
        $items=DB::table('simple_manufacturing_items')->join('items','items.id','=','simple_manufacturing_items.item_id')
        ->select('simple_manufacturing_items.*','items.title')->where('simple_manufacturing_items.simple_manufacturing_id',$process->id)->get();
        // dd($items);
        return view('admin.simplemanufacture.detail',compact('process','items'));
    }

    public function cancel(Request $request){
        $process=SimpleManufacturing::where('id',$request->id)->first();
        $items=DB::select('select item_id,center_id,amount,type from simple_manufacturing_items where simple_manufacturing_id=?', [$request->id]);
        $process->canceled=true;
        $process->save();

        foreach ($items as $key => $item) {
            if($item->type==2){
                maintainStock($item->item_id,$item->amount,$item->center_id,'out');

            }else{
                maintainStock($item->item_id,$item->amount,$item->center_id,'in');
            }
        }

        return redirect()->back();
    }
}
