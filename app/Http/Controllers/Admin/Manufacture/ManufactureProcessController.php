<?php

namespace App\Http\Controllers\Admin\Manufacture;

use App\Http\Controllers\Controller;
use App\Models\CenterStock;
use App\Models\ManufacturedProduct;
use App\Models\ManufactureProcess;
use App\Models\ManufactureProcessItem;
use App\Models\ManufactureWastage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class ManufactureProcessController extends Controller
{
    /*status
    ** 1=pending
    ** 2=processing
    ** 3=processing
    **
     */
    //
    public function index(Request $request)
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
                    (env('multi_package', false) ? 'conversions.name as unit,' : 'item.unit,')
                    . 'manufacture_processes.id,
                    manufacture_processes.expected,
                    manufacture_processes.start,
                    manufacture_processes.stage,
                    manufacture_processes.expected_end'
            )
        )
            ->get();
        return view('admin.manufacture.process.index', compact('processes'));
    }



    public function checkStock(Request $request)
    {
        $items = db::select('select m.id,m.item_id,i.stock from manufactured_product_items m join items i on m.item_id=i.id where m.id in (' . implode(',', $request->manufactured_product_item_ids) . ')');
        $msgs = [];
        foreach ($items as $key => $item) {
            $amount = $request->input('amount_' . $item->id);
            if (env('multi_stock', false)) {
                $center_id = $request->input('center_id_' . $item->id);
                $centerStock = DB::table('center_stocks')->where('item_id', $item->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                if ($centerStock == null) {
                    array_push($msgs, ['id' => $item->id]);
                } else {
                    if ($centerStock->amount < $amount) {
                        array_push($msgs, ['id' => $item->id]);
                    }
                }
            } else {
                if ($item->stock < $amount) {
                    array_push($msgs, ['id' => $item->id]);
                }
            }
        }
        return response()->json(['hasstock' => count($msgs) == 0, 'msgs' => $msgs]);
    }

    public function checkStockSaved($id)
    {

        $items = db::select('select
         mp.id,
         m.item_id,
         i.stock,
         i.title,
         mp.center_id,
         mp.amount
         from manufactured_product_items m
         join items i
         on m.item_id=i.id
         join manufacture_process_items mp
         on mp.manufactured_product_item_id=m.id
         where mp.manufacture_process_id = ?', [$id]);
        $msgs = [];
        foreach ($items as $key => $item) {
            $amount = $item->amount;
            if (env('multi_stock', false)) {
                $center_id = $item->center_id;
                $centerStock = DB::table('center_stocks')->where('item_id', $item->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                if ($centerStock == null) {
                    array_push($msgs, ['id' => $item->id]);
                } else {
                    if ($centerStock->amount < $amount) {
                        array_push($msgs, ['id' => $item->id]);
                    }
                }
            } else {
                if ($item->stock < $amount) {
                    array_push($msgs, ['id' => $item->id]);
                }
            }
        }
        return response()->json(['hasstock' => count($msgs) == 0, 'msgs' => $msgs]);
    }


    public function detail($id, Request $request)
    {
        $multiStock = env('multi_stock', false);
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
                    '.($multiStock?'centers.name as center,':'').'
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
                    . 'manufactured_product_items.item_id,' .
                    (env('multi_package', false) ? 'conversions.name as unit,' : 'items.unit,')
                    . 'manufacture_process_items.*'
            )
        )
            ->where('manufacture_process_items.manufacture_process_id', $process->id)->get();


        $wastages=[];
        if ($process->stage > 2) {

            if (env('multi_package', false)) {

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
            }
        }
        return view('admin.manufacture.process.detail', compact('wastages','items', 'process', 'multiStock'));
    }

    public function add(Request $request)
    {
        $multiStock = env('multi_stock', false);
        if ($request->getMethod() == "POST") {
            $process = new ManufactureProcess;
            $process->manufactured_product_id = $request->manufactured_product_id;
            $process->start = $request->start;
            $process->center_id = $request->center_id;
            $process->expected_end = $request->expected_end;
            $process->stage = $request->stage;
            $process->expected = $request->expected;
            $process->conversion_id = $request->conversion_id;
            if ($process->stage == 3) {
                $process->finished = 1;
            }
            $process->save();
            foreach ($request->manufactured_product_item_ids as $key => $manufactured_product_item_id) {
                $item = new ManufactureProcessItem;
                $item->manufacture_process_id = $process->id;
                $item->manufactured_product_item_id = $manufactured_product_item_id;
                $item->amount = $request->input('amount_' . $manufactured_product_item_id);
                $item->center_id = $request->input('center_id_' . $manufactured_product_item_id);
                $item->save();
            }

            if ($process->stage > 1) {
                $items = db::select('select id,item_id from manufactured_product_items where id in (' . implode(',', $request->manufactured_product_item_ids) . ')');
                foreach ($items as $key => $item) {
                    $amount = $request->input('amount_' . $item->id);
                    DB::update('update items set stock = stock-? where id=?', [$amount, $item->item_id]);
                    if ($multiStock) {
                        $center_id = $request->input('center_id_' . $item->id);
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
            return redirect()->back()->with('message', 'Process added Sucessfully');
        } else {
            $units = DB::table('conversions')->get();
            $centers = DB::table('centers')->get(['id', 'name']);
            $products = DB::select('select
            ((p.day*86400000)+(p.hour*3600000)+(p.minute*60000)) as finish_ms,
             p.*,i.title,i.conversion_id
             from manufactured_products p join items i on i.id=p.item_id');
            //  dd($products);
            return view('admin.manufacture.process.add', compact('products', 'centers', 'units', 'multiStock'));
        }
    }



    public function checkCancelStock(Request $request, $items)
    {
        $arr = [];
        foreach ($items as $key => $item) {
            $amount = $request->input('amount_' . $item->id);
            if ($item->amount < $amount) {
                array_push($arr, $item->id);
            }
        }
        return $arr;
    }

    public function cancelProcess($id, Request $request)
    {

        $process = ManufactureProcess::where('id', $id)->first();
        $oldStage = $process->stage;

        if ($oldStage == 1) {
            $process->stage = 5;
            $process->save();
            return response()->json(['status' => true]);
        }
        $items = db::select('select
        mp.id,
        m.item_id,
        mp.center_id,
        mp.amount
        from manufactured_product_items m
        join manufacture_process_items mp
        on mp.manufactured_product_item_id=m.id
        where mp.manufacture_process_id = ?', [$id]);
        $returnType = $request->return_type;
        if ($returnType == 6) {
            $arr = $this->checkCancelStock($request, $items);
            if (count($arr) > 0) {
                return response()->json(['status' => false, 'wrong' => $arr]);
            }
        }

        $process->stage = $returnType;
        $process->save();

        if ($returnType == 5 && $oldStage > 1) {

            foreach ($items as $key => $item) {
                DB::update('update items set stock = stock+? where id=?', [$item->amount, $item->item_id]);
                if (env('multi_stock', false)) {
                    $center_id = $item->center_id;
                    $centerStock = CenterStock::where('item_id', $item->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                    if ($centerStock == null) {
                        $centerStock = new CenterStock();
                        $centerStock->amount =  $item->amount;
                        $centerStock->item_id = $item->item_id;
                        $centerStock->center_id = $center_id;
                    } else {
                        $centerStock->amount += $item->amount;
                    }
                    $centerStock->save();
                }
            }
        } elseif ($returnType == 6 && $oldStage > 1) {
            foreach ($items as $key => $item) {
                $wastage = new ManufactureWastage();
                $wastage->item_id = $item->item_id;
                $wastage->manufacture_process_id = $process->id;
                $amount = $item->amount - $request->input('amount_' . $item->id);
                DB::update('update items set stock = stock+? where id=?', [$amount, $item->item_id]);
                if (env('multi_stock', false)) {
                    $center_id = $item->center_id;
                    $wastage->center_id = $center_id;
                    $centerStock = CenterStock::where('item_id', $item->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                    if ($centerStock == null) {
                        $centerStock = new CenterStock();
                        $centerStock->amount =  $amount;
                        $centerStock->item_id = $item->item_id;
                        $centerStock->center_id = $center_id;
                    } else {
                        $centerStock->amount += $amount;
                    }
                    $centerStock->save();
                }
                $wastage->amount = $amount;
                $wastage->save();
            }
        }

        if ($oldStage == 3) {

            $product = DB::table('manufactured_products')->where('id', $process->manufactured_product_id)->first();
            DB::update('update items set stock = stock-? where id=?', [$process->actual, $product->item_id]);
            if (env('multi_stock')) {
                $center_id = $process->center_id;
                $centerStock = CenterStock::where('item_id', $product->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                if ($centerStock == null) {
                    $centerStock = new CenterStock();
                    $centerStock->amount = -1 * $process->actual;
                    $centerStock->item_id = $product->item_id;
                    $centerStock->center_id = $center_id;
                } else {
                    $centerStock->amount -= $process->actual;
                }
                $centerStock->save();
            }
        }
        return response()->json(['status' => true]);
    }


    public function startProcess($id, Request $request)
    {
        $process = ManufactureProcess::where('id', $id)->first();
        if ($process->stage > 1) {
            return redirect()->back();
        }
        $process->start = $request->start;
        $process->expected_end = $request->expected_end;
        $process->stage = 2;
        $process->save();
        $items = db::select('select
        mp.id,
        m.item_id,
        mp.center_id,
        mp.amount
        from manufactured_product_items m
        join manufacture_process_items mp
        on mp.manufactured_product_item_id=m.id
        where mp.manufacture_process_id = ?', [$id]);
        foreach ($items as $key => $item) {
            DB::update('update items set stock = stock-? where id=?', [$item->amount, $item->item_id]);
            if (env('multi_stock', false)) {
                $center_id = $item->center_id;
                $centerStock = CenterStock::where('item_id', $item->item_id)->where('center_id', $center_id)->select('id', 'amount')->first();
                if ($centerStock == null) {
                    $centerStock = new CenterStock();
                    $centerStock->amount = -1 * $item->amount;
                    $centerStock->item_id = $item->item_id;
                    $centerStock->center_id = $center_id;
                } else {
                    $centerStock->amount -= $item->amount;
                }
                $centerStock->save();
            }
        }
        return response('ok');
    }

    public function finishProcess($id, Request $request)
    {

        $process = ManufactureProcess::where('id', $id)->first();
        if ($process->stage == 3) {
            return redirect()->back();
        }
        $process->end = $request->end;
        $process->actual = $request->actual;
        $process->stage = 3;
        $process->save();

        $items = db::select('select
        mp.id,
        m.item_id,
        mp.center_id,
        mp.amount
        from manufactured_product_items m
        join manufacture_process_items mp
        on mp.manufactured_product_item_id=m.id
        where mp.manufacture_process_id = ?', [$id]);
        foreach ($items as $key => $item) {
            if ($request->filled('amount_' . $item->id)) {
                $amount = $request->input('amount_' . $item->id);
                if ($amount > 0) {

                    $wastage = new ManufactureWastage();
                    $wastage->item_id = $item->item_id;
                    $wastage->manufacture_process_id = $process->id;
                    DB::update('update items set stock = stock-? where id=?', [$amount, $item->item_id]);
                    if (env('multi_stock', false)) {
                        $center_id = $item->center_id;
                        $wastage->center_id = $center_id;
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
                    $wastage->amount = $amount;
                    $wastage->save();
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
        return redirect()->back();
    }



    public function setProcessing($id, Request $request)
    {
        $items =  DB::table('manufacture_process_items')
            ->join('manufactured_product_items', 'manufactured_product_items.id', '=', 'manufacture_process_items.manufactured_product_item_id')
            ->join('items', 'items.id', '=', 'manufactured_product_items.item_id')
            ->select('manufacture_process_items.item_id,manufacture_process_items.center_id')->get();
    }

    public function loadTemplate(Request $request)
    {
        $templateData = DB::table('manufactured_product_items')
            ->join('items', 'items.id', '=', 'manufactured_product_items.item_id');
        if (env('multi_stock', false)) {
            $templateData = $templateData->join('conversions', 'conversions.id', '=', 'items.conversion_id');
        }
        $templateData = $templateData->select(DB::raw('concat(items.title,\'(\',' . (env('multi_package', false) ? 'conversions.name' : 'items.unit') . ',\')\') as title,manufactured_product_items.amount,manufactured_product_items.id'))
            ->where('manufactured_product_items.manufactured_product_id', $request->id)
            ->get();
        return response()->json(
            $templateData
        );
    }
}
