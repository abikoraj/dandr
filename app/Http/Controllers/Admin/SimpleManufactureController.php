<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManufacturedProduct;
use App\Models\MilkDay;
use App\Models\SimpleManufacturing;
use App\Models\SimpleManufacturingItem;
use App\NepaliDate;
use App\NepaliDateHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpleManufactureController extends Controller
{



    public function retriveBatches($id)
    {
        $milk_id = env('milk_id', null);
        if ($id == $milk_id) {
            $startdate = env('use_batch_from', 20790601);
            // $batches=DB::select("select c.amount,date as batchno from 
            // (select sum(m_amount+e_amount)-ifnull((select sum(amount) from milk_days where date=milkdatas.date ),0) as amount,date from milkdatas  where date>= {$startdate} group by date) c 
            // where c.amount>0");
            $batches = DB::select("select c.amount,date as batch_no from 
            (select sum(m_amount+e_amount)-
            ifnull((select sum(amount) from milk_days where date=milkdatas.date ),0) -
            ifnull((select sum(bi.qty) from bill_items bi join bills b on bi.bill_id=b.id where bi.item_id={$id} and b.date=milkdatas.date ),0)-
            ifnull((select sum(qty) from sellitems where item_id={$id} and date=milkdatas.date ),0)
            as amount,date from milkdatas  where date>={$startdate}  group by date) c 
            where c.amount>0");
            return response()->json(['data' => $batches, 'type' => 'milk']);
        } else {
            $type = ManufacturedProduct::where('item_id', $id)->count() > 0 ? 'others' : 'nobatch';

            $data = [];
            if($type=='nobatch'){
                $connectedItem = DB::table('connected_items')->where('item_id', $id)->first();
                if ($connectedItem != null) {
                    $id = $connectedItem->target_item_id;
                    $type='connected';
                }

            }
            if ($type != 'nobatch') {
                $finisedBatches = DB::table('batch_finisheds')->where('item_id')->pluck('batch_id');
                $finisedBatcheSTR = count($finisedBatches) > 0 ? ' and id not in (' . implode(',', $finisedBatches->toArray()) . ")" : "";
                $data = DB::select("select c.id as batch_id,c.amount,c.batch_no from(select id,(amount-
                ifnull((select sum(qty) from bill_items where batch_id=simple_manufacturing_items.id and to_batch_id is null ),0) -
                ifnull((select sum(qty) from sellitems where batch_id=simple_manufacturing_items.id ),0) -
                ifnull((select sum(s.amount) from simple_manufacturing_items s where s.batch_id=simple_manufacturing_items.id ),0)) as amount,batch_no
                 from simple_manufacturing_items where item_id={$id} and batch_no is not null {$finisedBatcheSTR}) c where c.amount>0");
            } 
            
            return response()->json(['data' => $data, 'type' => $type,'id'=>$id])->setEncodingOptions(JSON_NUMERIC_CHECK);
        }
    }
    public function index(Request $request)
    {
        if ($request->getMethod() == "POST") {

            $processes_query = DB::table('simple_manufacturings')->where('canceled', 0);
            $processes = rangeSelector($request, $processes_query)->get();
            if ($processes->count() > 0) {
                $ids = "(" . implode(",", $processes->pluck('id')->toArray() ?? []) . ")";
                $items = collect(DB::select("select mi.*,i.title from (select item_id,type,sum(amount) as amount from simple_manufacturing_items 
                where simple_manufacturing_id in {$ids}  group by item_id, type) mi join items i on i.id = mi.item_id"));
            } else {
                $items = collect([]);
            }
            // dd($processes,$items);
            return view('admin.simplemanufacture.data', compact('processes', 'items'));
        } else {
            return view('admin.simplemanufacture.index');
        }
    }

    public function add(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $date = getNepaliDate($request->date);
            // $item_ids="(".implode(",",$request->item_ids) . ")";
            $items = DB::table('items')->select(DB::raw(' id,cost_price,sell_price'))->whereIn('id', $request->item_ids)->get();
            // dd($request->all());
            $process = new SimpleManufacturing();
            $process->date = $date;
            $process->title = '';
            $process->save();
            $titles = [];
            $milk_id = env('milk_id', null);

            foreach ($request->items as $key => $_value) {
                $value = (object)$_value;
                $item = new SimpleManufacturingItem();
                $item->type = $value->type;
                $item->center_id = $value->center_id;
                $item->item_id = $value->item_id;
                $item->amount = $value->amount;
                $item->amount = $value->amount;
                $item->simple_manufacturing_id = $process->id;
                $localItem = $items->where('id', $item->item_id)->first();
                if ($value->batch_id != null) {
                    if ($item->item_id == $milk_id) {
                        $batchDate = getNepaliDate($value->batch_id);
                        $day = MilkDay::where('date', $batchDate)->first();
                        if ($day == null) {
                            $day = new MilkDay();
                            $day->date = $batchDate;
                            $day->amount = 0;
                        }
                        $day->amount += $item->amount;
                        $day->save();
                        $item->date = $batchDate;
                    } else {
                        $item->batch_id = $value->batch_id;
                    }
                }
                if ($localItem != null) {
                    $item->rate = implode("|", [$localItem->cost_price, $localItem->sell_price]);
                }

                if ($item->type == 2) {
                    $product = DB::table('manufactured_products')->where('item_id', $item->item_id)->first();
                    if ($product != null) {
                        $d = NepaliDateHelper::withDate($date);
                        $item->expiry = $d->addDays($product->expairy_days);
                    }
                    maintainStock($item->item_id, $item->amount, $item->center_id, 'in');
                    array_push($titles, "({$value->item_title} X {$item->amount})");
                    $item->batch_no = $request->date . "#" . $process->id;
                } else {
                    maintainStock($item->item_id, $item->amount, $item->center_id, 'out');
                }

                $item->save();
            }

            $process->title = implode(",", $titles);
            $process->save();
            return response($process);
        } else {

            $items = DB::table('items')->get(['id', 'title']);
            $centerStocks = DB::table('center_stocks')->whereIn('item_id', $items->pluck('id'))->get(['center_id', 'amount', 'item_id']);
            $centers = DB::table('centers')->get(['id', 'name']);
            if (env('use_batch_manufacture', false)) {
                return view('admin.simplemanufacturebatch.add', compact('items', 'centers', 'centerStocks'));
            } else {
                return view('admin.simplemanufacture.add', compact('items', 'centers', 'centerStocks'));
            }
        }
    }

    public function detail(SimpleManufacturing $process)
    {
        $items = DB::table('simple_manufacturing_items')
            ->join('items', 'items.id', '=', 'simple_manufacturing_items.item_id')
            ->select('simple_manufacturing_items.*', 'items.title')
            ->where('simple_manufacturing_items.simple_manufacturing_id', $process->id)->get();
        // dd($items);
        return view('admin.simplemanufacture.detail', compact('process', 'items'));
    }

    public function cancel(Request $request)
    {

        $process = SimpleManufacturing::where('id', $request->id)->first();
        $items = DB::select('select item_id,center_id,amount,type,date from simple_manufacturing_items where simple_manufacturing_id=?', [$request->id]);
        // $process->canceled=true;
        // $process->save();

        $milk_id = env('milk_id');

        foreach ($items as $key => $item) {
            if ($item->type == 2) {
                maintainStock($item->item_id, $item->amount, $item->center_id, 'out');
            } else {

                maintainStock($item->item_id, $item->amount, $item->center_id, 'in');
                if ($item->item_id == $milk_id) {
                    DB::update('update milk_days set amount=amount-? where date=?', [$item->amount, $item->date]);
                }
            }
        }
        DB::delete('delete from simple_manufacturing_items where simple_manufacturing_id=?', [$request->id]);
        $process->delete();

        return redirect()->back();
    }
}
