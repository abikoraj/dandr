<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CenterStock;
use App\Models\ManufactureProcess;
use App\Models\ManufactureUnusedItem;
use App\Models\ManufactureWastage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufactureController extends Controller
{
    public function list(Request $request)
    {
        $processes = DB::table('manufacture_processes')
            ->join('manufactured_products', 'manufactured_products.id', '=', 'manufacture_processes.manufactured_product_id')
            ->join('items', 'items.id', '=', 'manufactured_products.item_id');
        if (env('multi_package', false)) {
            $processes = $processes->join('conversions', 'conversions.id', '=', 'items.conversion_id');
        }
        $processes = $processes->select(
            DB::raw(
                'items.title,' .
                    (env('multi_package', false) ? 'conversions.name as unit,' : 'items.unit,')
                    . 'manufacture_processes.id,
                manufacture_processes.expected,
                manufacture_processes.start,
                manufacture_processes.stage,
                manufacture_processes.expected_end'
            )
        )
        ->where('manufacture_processes.stage',2)
        ->get();
        return response()->json($processes);
    }

    public function detail($id)
    {
        $multiStock = env('multi_stock', false);

        $details=$this->getDetail($id);
        $process=$details[0];
        $items=$details[1];
        $wastages=[];
        $unused=[];
        if ($process->stage > 2) {

            if (env('multi_package', false)) {
                $unused=DB::select("select w.id,i.title,w.amount,c.name as unit
                from manufacture_unused_items w
                join items i on i.id=w.item_id
                join conversions c on c.id=i.conversion_id
                where w.manufacture_process_id=?", [$process->id]);
                $wastages = DB::select("select w.id,i.title,w.amount,c.name as unit
                from manufacture_wastages w
                join items i on i.id=w.item_id
                join conversions c on c.id=i.conversion_id
                where w.manufacture_process_id=?", [$process->id]);
            } else {
                $wastages = DB::select("select w.id,i.title,w.amount,i.unit
                from manufacture_wastages w
                join items i on i.id=w.item_id
                where w.manufacture_process_id=?", [$process->id]);
                $unused = DB::select("select w.id,i.title,w.amount,i.unit
                from manufacture_unused_items w
                join items i on i.id=w.item_id
                where w.manufacture_process_id=?", [$process->id]);
            }
        }
        return response()->json(compact('wastages','items', 'process','unused'));
    }

    public function getDetail($id)
    {
        $process = DB::table('manufacture_processes')
            ->join('manufactured_products', 'manufactured_products.id', '=', 'manufacture_processes.manufactured_product_id')
            ->join('items', 'items.id', '=', 'manufactured_products.item_id');

        if (env('multi_stock', false)) {

            $process = $process->join('centers', 'centers.id', '=', 'manufacture_processes.center_id');
        }

        if (env('multi_package', false)) {
            $process = $process->join('conversions', 'conversions.id', '=', 'items.conversion_id');
        }

        $process = $process->select(
            DB::raw(
                'items.title,
                ((manufactured_products.day*86400000)+(manufactured_products.hour*3600000)+(manufactured_products.minute*60000)) as finish_ms,
                    '.(env('multi_stock',false)?'centers.name as center,':'').'
                    manufactured_products.item_id,'
                    .(env('multi_package',false)?'conversions.name as unit,':'items.unit,').
                    'manufacture_processes.*'
            )
        )
            ->where('manufacture_processes.id', $id)->first();


        $items =  DB::table('manufacture_process_items')
            ->join('manufactured_product_items', 'manufactured_product_items.id', '=', 'manufacture_process_items.manufactured_product_item_id')
            ->join('items', 'items.id', '=', 'manufactured_product_items.item_id');
        if (env('multi_stock', false)) {

            $items = $items->join('centers', 'centers.id', '=', 'manufacture_process_items.center_id');
        }

        if (env('multi_package', false)) {
            $items = $items->join('conversions', 'conversions.id', '=', 'items.conversion_id');
        }



        $items = $items->select(
            DB::raw(
                'items.title,' .
                    (env('multi_stock', false) ? 'centers.name as center,' : '')
                    . 'manufactured_product_items.item_id,manufactured_product_items.amount as item_amount,' .
                    (env('multi_package', false) ? 'conversions.name as unit,' : 'items.unit,')
                    . 'manufacture_process_items.*'
            )
        )
            ->where('manufacture_process_items.manufacture_process_id', $process->id)->get();
            return [$process,$items];
    }

    public function finish(Request $request,$id){
        $process = ManufactureProcess::where('id', $id)->first();
        // if ($process->stage >= 3) {
        //     return response()->json(['message'=>"Process Already Closed"],500);
        // }
        $process->end = $request->end;
        $process->actual = $request->actual;
        $process->stage = 3;
        $process->save();


        $items = db::select('select
        m.id as manu_item_id,
        mp.id,
        m.item_id,
        mp.center_id,
        mp.amount,
        i.cost_price,
        i.sell_price
        from manufactured_product_items m
        join manufacture_process_items mp
        on mp.manufactured_product_item_id=m.id
        join items i on  i.id=m.item_id
        where mp.manufacture_process_id = ?', [$id]);
        $wastages=collect(
            array_map(function($arr){
                return (object)$arr;
            }, $request->wastages)
        );
        $unusedItems=collect(
            array_map(function($arr){
                return (object)$arr;
            }, $request->unusedItems)
        );
        // dd($wastages,$unusedItems);

        foreach ($items as $key => $item) {
            $amount=0;
            $center_id = $item->center_id;
            $_wastage=$wastages->where('manufactured_product_item_id',$item->manu_item_id)->first();
            // dd($_wastage->amount);
            if ($_wastage!=null) {
                $amount = $_wastage->amount;

                if ($amount > 0) {
                    $wastage = new ManufactureWastage();
                    $wastage->item_id = $item->item_id;
                    $wastage->manufacture_process_id = $process->id;
                    $wastage->rate=$item->cost_price;
                    $wastage->amount =  $_wastage->amount;
                    $wastage->center_id = $center_id;
                    $wastage->save();
                }
            }
            $_unusedItem=$unusedItems->where('manufactured_product_item_id',$item->manu_item_id)->first();
            if ($_unusedItem !=null) {
                $unusedAmount = $_unusedItem->amount;
                $amount-=$unusedAmount;
                if ($unusedAmount > 0) {
                    $unusedItem = new ManufactureUnusedItem();
                    $unusedItem->item_id = $item->item_id;
                    $unusedItem->center_id = $center_id;
                    $unusedItem->manufacture_process_id = $process->id;
                    $unusedItem->amount = $_unusedItem->amount;
                    $unusedItem->save();

                }
            }

            // dd($_wastage,$_unusedItem,$amount);



            if($amount!=0){
                if($amount<0){
                    DB::update('update items set stock = stock+? where id=?', [(-1*$amount), $item->item_id]);
                }else{
                    DB::update('update items set stock = stock-? where id=?', [$amount, $item->item_id]);
                }

                if (env('multi_stock', false)) {
                    $centerStock = CenterStock::where('item_id', $item->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                    if ($centerStock == null) {
                        $centerStock = new CenterStock();
                        $centerStock->amount = -1 * $amount;
                        $centerStock->item_id = $item->item_id;
                        $centerStock->center_id = $center_id;
                    } else {
                        $centerStock->amount -= $amount;
                    }
                    $centerStock->save();
                }
            }
        }

        $product = DB::table('manufactured_products')->where('id', $process->manufactured_product_id)->first();
        DB::update('update items set stock = stock+? where id=?', [$process->actual, $product->item_id]);
        if (env('multi_stock')) {
            $center_id = $process->center_id;
            $centerStock = CenterStock::where('item_id', $product->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
            if ($centerStock == null) {
                $centerStock = new CenterStock();
                $centerStock->amount =  $process->actual;
                $centerStock->item_id = $product->item_id;
                $centerStock->center_id = $center_id;
            } else {
                $centerStock->amount += $process->actual;
            }
            $centerStock->save();
        }


        return $this->detail($id);
    }
}
