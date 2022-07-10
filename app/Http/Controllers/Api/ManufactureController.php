<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

}
