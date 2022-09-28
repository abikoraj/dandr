<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\StockOut;
use App\Models\StockOutItem;
use App\NepaliDate;
use App\NepaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //
    public function index(Request $request)
    {
        $items = [];

        if ($request->getMethod() == "POST") {
            $query = Item::where('id', '>', 0);
            $query1 = Item::where('id', '>', 0);
            $step = $request->step ?? 0;
            $countStep = env('countstep', 24);
            $data = [];
            $data['page'] = $step;
            if ($request->filled('keyword')) {
                $query = $query->where('title', 'like', $request->keyword . '%');
                $query1 = $query1->where('title', 'like', $request->keyword . '%');
                $data['key'] = $request->keyword;
            }
            // $temp=$query;
            if ($step == 0) {
                $query = $query->take($countStep);
            } else {
                $query = $query->skip($step * $countStep)->take($countStep);
            }
            $items = $query->select(DB::raw('id,title,sell_price,stock,unit,reward_percentage,number,(select name from conversions where id=items.conversion_id) as cunit'))->latest()->get();
            $data['total'] = $query1->count();
            $data['items'] = $items;

            return response()->json($data);
        } else {

            $large = env('large', false);
            if (!$large) {
                $items = DB::table('items')->select(DB::raw('id,title,sell_price,stock,unit,reward_percentage,number,(select name from conversions where id=items.conversion_id) as cunit'))->latest()->get();
            }

            $centers = DB::table('centers')->get(['id', 'name']);
            $units = Db::table('conversions')->select('id', 'name', 'is_base')->get();
            return view('admin.item.index', compact('items', 'large', 'centers', 'units'));
        }
    }

    public function all()
    {
        $items = Item::select('id', 'title', 'number')->where('posonly', 1)->get();
        return response()->json($items);
    }
    public function barcode(Request $request)
    {
        if ($request->filled('exact')) {
            $items = DB::table('items')->where('number', $request->keyword)->select('id', 'sell_price', 'title', 'number')->take(1)->get();
        } else {
            $items = Item::where('number', 'like', $request->keyword . '%')->select('id', 'sell_price', 'title', 'number')->take(24)->get();
        }
        return response()->json($items);
    }

    public function product(Request $request)
    {
        $items = Item::where('title', 'like', $request->keyword . '%')->where('disonly', 1)->select('id', 'dis_price', 'title', 'dis_number', 'sell_price', 'number')->take(24)->get();
        return response()->json($items);
    }

    public function productBarcode(Request $request)
    {
        $items = Item::where('dis_number', 'like', $request->keyword . '%')->where('disonly', 1)->select('id', 'dis_price', 'title', 'dis_number', 'sell_price', 'number')->take(24)->get();
        return response()->json($items);
    }


    public function save(Request $request)
    {
        $item = new Item();
        $item->title = $request->name;
        $item->number = $request->number;
        $item->cost_price = $request->cost_price ?? 0;
        $item->sell_price = $request->sell_price ?? 0;
        $item->stock = $request->stock ?? 0;
        if ($request->filled('unit')) {
            $item->unit = $request->unit ?? '--';
        }
        $item->wholesale = $request->wholesale ?? 0;
        $item->reward_percentage = $request->reward;
        $item->points = $request->points;
        if ($request->filled('conversion_id')) {
            $item->conversion_id = $request->conversion_id;
            $item->unit = DB::table('conversions')->where('id', $request->conversion_id)->select('name')->first()->name;
        }
        if ($request->hasFile('image')) {
            $item->image = $request->image->store('uploads/item');
        }
        $item->trackstock = $request->trackstock ?? 0;
        $item->trackexpiry = $request->trackexpiry ?? 0;
        $item->sellonline = $request->sellonline ?? 0;
        $item->disonly = $request->disonly ?? 0;
        $item->posonly = $request->posonly ?? 0;
        $item->farmeronly = $request->farmeronly ?? 0;

        $item->taxable = $request->taxable ?? 0;
        $item->tax = $request->tax;

        $item->description = $request->description;
        $item->minqty = $request->minqty;
        $item->expirydays = $request->expirydays;
        $item->dis_number = $request->dis_number ?? 0;
        $item->dis_price = $request->dis_price ?? 0;

        $item->save();

        if (env('multi_stock', false)) {
            if ($request->filled('centers')) {
                foreach ($request->centers as $key => $center_id) {
                    $amount = $request->input('qty_' . $center_id);
                    $rate = $request->input('rate_' . $center_id) ?? 0;
                    $wholesale = $request->input('wholesale_' . $center_id) ?? 0;
                    $stock = new CenterStock();
                    $stock->item_id = $item->id;
                    $stock->center_id = $center_id;
                    $stock->amount = $amount;
                    $stock->rate = $rate;
                    $stock->wholesale = $wholesale;
                    $stock->save();
                    $item->stock += $stock->amount;
                    if ($center_id == env('maincenter', -1)) {
                        $item->sell_price = $rate;
                        $item->wholesale = $wholesale;
                    }
                }
            }
            $item->save();
        }
        if ($request->filled('rettype')) {
            return response()->json($item);
        } else {
            if (env('multi_package', false)) {
                $item->cunit = DB::selectOne('select name from conversions where id=?', [$item->conversion_id])->name;
            }
            return view('admin.item.singlenew', compact('item'));
        }
    }

    public function edit(Request $request)
    {
        $item = Item::where('id', $request->id)->first();
        $centers = DB::table('centers')->get(['id', 'name']);
        $units = DB::table('conversions')->get(['id', 'name']);
        // dd($item);
        return view('admin.item.edit', compact('item', 'centers', 'units'));
    }

    public function update(Request $request)
    {
        $item = Item::where('id', $request->id)->first();
        $item->title = $request->name;
        $item->number = $request->number;
        $item->cost_price = $request->cost_price ?? 0;
        $item->sell_price = $request->sell_price ?? 0;
        if ($request->filled('stock')) {
            $item->stock = $request->stock ?? 0;
        }
        if ($request->filled('unit')) {

            $item->unit = $request->unit ?? '--';
        }
        $item->reward_percentage = $request->reward;
        $item->points = $request->points;
        if ($request->filled('conversion_id')) {
            $item->conversion_id = $request->conversion_id;
            $item->unit = DB::table('conversions')->where('id', $request->conversion_id)->select('name')->first()->name;
        }

        if ($request->hasFile('image')) {
            $item->image = $request->image->store('uploads/item');
        }

        $item->trackstock = $request->trackstock ?? 0;
        $item->trackexpiry = $request->trackexpiry ?? 0;
        $item->sellonline = $request->sellonline ?? 0;
        $item->disonly = $request->disonly ?? 0;
        $item->posonly = $request->posonly ?? 0;
        $item->farmeronly = $request->farmeronly ?? 0;
        $item->taxable = $request->taxable ?? 0;
        $item->tax = $request->tax;
        $item->description = $request->description;
        $item->minqty = $request->minqty;
        $item->expirydays = $request->expirydays;
        $item->dis_number = $request->dis_number ?? '';
        $item->dis_price = $request->dis_price ?? 0;
        $item->save();

        if ($request->filled('center_ids')) {
            $newstock = 0;
            foreach ($request->center_ids as $center_id) {
                $amount = $request->input('amount_' . $center_id);
                $rate = $request->input('rate_' . $center_id) ?? 0;
                $wholesale = $request->input('wholesale_' . $center_id) ?? 0;
                $stock = CenterStock::where('item_id', $item->id)->where('center_id', $center_id)->first();
                if ($stock == null) {
                    $stock = new CenterStock();
                    $stock->item_id = $item->id;
                    $stock->center_id = $center_id;
                }
                $stock->amount = $amount;
                $stock->rate = $rate;
                $stock->wholesale = $wholesale;
                $stock->save();
                if ($center_id == env('maincenter', -1)) {
                    $item->sell_price = $rate;
                    $item->wholesale = $wholesale;
                }
                $newstock += $stock->amount;
            }

            $item->stock = $newstock;
            $item->save();
        }
        if (env('multi_package', false)) {
            $item->cunit = DB::selectOne('select name from conversions where id=?', [$item->conversion_id])->name;
        }
        return view('admin.item.singlenew', compact('item'));
    }

    public function delete($id)
    {
        $tables = [
            ["bill_items", "item_id"],
            ["center_stocks", "item_id"],
            ["credit_note_items", "item_id"],
            ["free_items", "item_id"],
            ["item_units", "item_id"],
            ["item_variants", "item_id"],
            ["manufactured_products", "item_id"],
            ["manufactured_product_items", "item_id"],
            ["manufacture_wastages", "item_id"],
            ["offer_items", "item_id"],
            ["pos_bill_items", "item_id"],
            ["repackage_items", "from_item_id"],
            ["repackage_items", "to_item_id"],
            ["sellitems", "item_id"],
            ["stock_out_items", "item_id"],
            ["supplierbillitems", "item_id"]
        ];
        $smallQuery = [];
        foreach ($tables as $key => $table) {
            array_push($smallQuery, "(select count(*) from {$table[0]} where {$table[0]}.{$table[1]}={$id})");
        }

        $query = "select " . implode("+", $smallQuery) . " as used";
        if (DB::selectOne($query)->used > 0) {
            DB::update('update items set archive = 1 where id = ?', [$id]);
        } else {
            DB::delete('delete items where id = ?', [$id]);
        }

        return response('ok');
    }

    public function centerStock($id, Request $request)
    {
        if (env('multi_stock', false)) {

            $maincenter=env('maincenter',null);
            $totalStock = 0;
            $item = Item::find($id);
            if ($request->getMethod() == "POST") {
                foreach ($request->center_ids as $center_id) {
                    $amount = $request->input('amount_' . $center_id);
                    $rate = $request->input('rate_' . $center_id) ?? 0;
                    $wholesale = $request->input('wholesale_' . $center_id) ?? 0;
                    $stock = CenterStock::where('item_id', $id)->where('center_id', $center_id)->first();
                    if ($stock == null) {
                        $stock = new CenterStock();
                        $stock->item_id = $id;
                        $stock->center_id = $center_id;
                    }

                    $stock->amount = $amount;
                    $stock->rate = $rate;
                    $stock->wholesale = $wholesale;
                    $stock->save();
                    $totalStock += $amount;
                    if($center_id=$maincenter){
                        $item->sell_price=$stock->rate;
                        $item->wholesale=$stock->wholesale;
                    }
                }
                $item->stock = $totalStock;
                $item->save();
                return redirect()->back()->with('msg', 'Stock Updated Sucessfully');
            } else {
                $centers = Center::select('id', 'name')->get();
                return view('admin.item.stock', compact('item', 'centers'));
            }
        } else {
            return redirect()->route('admin.item.index');
        }
    }


    public function stockOut(Request $request)
    {
        $items = [];
        if ($request->getMethod() == "GET") {
            $centers = DB::table('centers')->get(['id', 'name']);
            $items = DB::table('items')->get(['id', 'title', 'number']);

            return view('admin.item.stockout', compact('items', 'centers'));
        } else {
            $sout = new StockOut();
            $date = str_replace('-', '', $request->info['date']);
            $center_id =  $request->info['center_id'];
            $from_center_id =  $request->info['from_center_id'];
            // dd($request->items);
            $ids = [];
            try {
                //code...
                $sout = StockOut::create([
                    'date' => $date,
                    'center_id' => $center_id,
                    'from_center_id' => $from_center_id,
                ]);
                foreach ($request->items as $key => $item) {
                    $sout_item = StockOutItem::create([
                        'item_id' => $item['item_id'],
                        'amount' => $item['qty'],
                        'stock_out_id' => $sout->id
                    ]);

                    array_push($ids, $sout_item->id);

                    $item = DB::table('items')->where('id', $sout_item->item_id)->first(['sell_price', 'wholesale']);
                    $outstock = CenterStock::where('item_id', $sout_item->item_id)->where('center_id', $from_center_id)->first();
                    $instock = CenterStock::where('item_id', $sout_item->item_id)->where('center_id', $center_id)->first();
                    if ($instock == null) {
                        $instock = new CenterStock();
                        $instock->item_id = $sout_item->item_id;
                        $instock->center_id = $center_id;
                        $instock->amount = $sout_item->amount;
                        $instock->rate = $item->sell_price;
                        $instock->wholesale = $item->wholesale;
                    } else {
                        $instock->amount += $sout_item->amount;
                    }
                    $instock->save();
                    if ($outstock == null) {
                        $outstock = new CenterStock();
                        $outstock->item_id = $sout_item->item_id;
                        $outstock->center_id = env('maincenter');
                        $outstock->amount = -1 * $sout_item->amount;
                        $outstock->rate = $item->sell_price;
                        $outstock->wholesale = $item->wholesale;
                    } else {
                        $outstock->amount -= $sout_item->amount;
                    }
                    $outstock->save();
                }
                return response($sout->id);
            } catch (\Throwable $th) {
                if ($sout->id == null || $sout->id == 0) {
                    $sout->delete();
                }
                if (count($ids) > 0) {
                    StockOutItem::whereIn('id', $ids)->delete();
                }
                return abort(500, $th->getMessage());
            }
        }
    }

    public function stockOutList()
    {
        $stockOuts = DB::table('stock_outs')->orderBy('stock_outs.id', 'desc')->get();
        $centers = DB::table('centers')->get(['id', 'name']);
        // dd($stockOuts);
        return view('admin.item.stockoutlist', compact('stockOuts', 'centers'));
    }

    public function stockOutView($id)
    {
        $stockOut = DB::table('stock_outs')->join('centers', 'centers.id', '=', 'stock_outs.center_id')->select(
            'stock_outs.date',
            'stock_outs.id',
            'stock_outs.canceled',
            'centers.name'
        )->where('stock_outs.id', $id)->first();
        $stockOutItems = DB::table('stock_out_items')
            ->join('items', 'items.id', '=', 'stock_out_items.item_id')
            ->where('stock_out_items.stock_out_id', $id)
            ->get(['stock_out_items.amount', 'items.title', 'stock_out_items.id']);
        return view('admin.item.stockoutview', compact('stockOut', 'stockOutItems', 'id'));
    }
    public function stockOutCancel($id)
    {
        DB::table('stock_outs')->where('stock_outs.id', $id)->update(['canceled' => 1]);
        $stockOut = DB::table('stock_outs')->where('id', $id)->first();
        $stockOutItems = DB::table('stock_out_items')
            ->where('stock_out_id', $id)->get(['item_id', 'amount']);
        foreach ($stockOutItems as $key => $item) {
            $cstock = CenterStock::where('item_id', $item->item_id)->where('center_id', $stockOut->center_id)->first();
            $istock = CenterStock::where('item_id', $item->item_id)->where('center_id', $stockOut->from_center_id ?? env('maincenter'))->first();
            if ($cstock != null) {
                $cstock->amount -= $item->amount;
                $cstock->save();
            }
            if ($istock != null) {
                $cstock->amount += $item->amount;
                $cstock->save();
            }
        }
    }

    public function stockOutPrint($id)
    {
        $stockOut = DB::table('stock_outs')->join('centers', 'centers.id', '=', 'stock_outs.center_id')->select(
            'stock_outs.date',
            'stock_outs.id',
            'stock_outs.canceled',
            'centers.name'
        )->where('stock_outs.id', $id)->first();
        $stockOutItems = DB::table('stock_out_items')
            ->join('items', 'items.id', '=', 'stock_out_items.item_id')
            ->where('stock_out_items.stock_out_id', $id)
            ->get(['stock_out_items.amount', 'items.title']);
        return view('admin.item.stockoutprint', compact('stockOut', 'stockOutItems', 'id'));
    }

    // XXX item stock tracking

    public function stockTracking(Request $request)
    {
        if ($request->isMethod('post')) {
            // dd($request->all());
            $milk_id = env('milk_id', null);
            $type = $request->type;
            $range = [];
            $data = [];

            // dd($manufactured_product_id,$manufactured_product_item_ids);
            
            $manufactured_product=DB::table('manufactured_products')->where('item_id',$request->item_id)->first(['id']);
            if($manufactured_product!=null){

                $manufacture1=rangeSelectorEng($request,DB::table('manufacture_processes')
                ->select(DB::raw('sum(actual) as qty,cast(start as date) as date,"manu" as type'))
                ->where('stage',3)
                ->where('manufactured_product_id',$manufactured_product->id)
                ->whereNotNull('start'),
                DB::raw('cast(start as date)'))->groupBy(DB::raw('cast(start as date)'))->get();

                foreach ($manufacture1 as $key => $local) {
                    // dd($local->date);
                    $local->date=NepaliDateHelper::nepDate($local->date);
                }
                $data = array_merge($data, $manufacture1->toArray());

            }


            $manufactured_product_item_ids=DB::table('manufactured_product_items')->where('item_id',$request->item_id)->pluck('id');
            if(count($manufactured_product_item_ids)>0){
                $rawMaterial1=rangeSelectorEng($request,DB::table('manufacture_process_items')
                ->join('manufacture_processes','manufacture_processes.id','=','manufacture_process_items.manufacture_process_id')
                ->select(DB::raw('sum(manufacture_process_items.amount) as qty,cast(manufacture_processes.start as date) as date,"raw" as type'))
                ->where('manufacture_processes.stage',3)
                ->whereIn('manufactured_product_item_id',$manufactured_product_item_ids)
                ->whereNotNull('manufacture_processes.start'),
                DB::raw('cast(manufacture_processes.start as date)'))->groupBy(DB::raw('cast(manufacture_processes.start as date)'))->get();
                foreach ($rawMaterial1 as $key => $local) {
            
                    $local->date=NepaliDateHelper::nepDate($local->date);
                }
                $data = array_merge($data, $rawMaterial1->toArray());
            }

            
            $wastage1=rangeSelectorEng($request,DB::table('manufacture_wastages')
            ->join('manufacture_processes','manufacture_processes.id','=','manufacture_wastages.manufacture_process_id')
            ->select(DB::raw('sum(manufacture_wastages.amount) as qty,cast(manufacture_processes.start as date) as date,"waste" as type'))
            ->where('manufacture_processes.stage',3)
            ->whereNotNull('manufacture_processes.start')
            ->whereNull('date'),
            DB::raw('cast(manufacture_processes.start as date)'))->groupBy(DB::raw('cast(manufacture_processes.start as date)'))->get();
            foreach ($wastage1 as $key => $local) {
        
                $local->date=NepaliDateHelper::nepDate($local->date);
            }
            $data = array_merge($data, $wastage1->toArray());


            $wastage=rangeSelectorEng($request,DB::table('manufacture_wastages')
            ->whereNotNull('date')
            ->select(DB::raw('sum(amount) as qty,date,"waste" as type')))->groupBy('date')->get();
            // dd($wastage);
            $data = array_merge($data, $wastage->toArray());

            // $item = Item::orderBy('id','desc');
            if ($request->item_id == $milk_id) {
                #Milk collected
                $milkCollection = rangeSelector($request, DB::table('milkdatas')->select(DB::raw('sum(m_amount+e_amount) as qty,date,"collect" as type')))->groupBy('date')->get();
                $data = array_merge($data, $milkCollection->toArray());
            }

            #raw material qty
            $rawMaterial = rangeSelector($request, DB::table('simple_manufacturing_items')
                ->join('simple_manufacturings', 'simple_manufacturings.id', "=", 'simple_manufacturing_items.simple_manufacturing_id')
                ->where('item_id', $request->item_id)
                ->where('type', 1)
                ->select(DB::raw('sum(amount) as qty,simple_manufacturings.date,"raw" as type')), 'simple_manufacturings.date')->groupBy('simple_manufacturings.date')->get();
            $data = array_merge($data, $rawMaterial->toArray());

            #manufactured qty
            $manufactured = rangeSelector($request, DB::table('simple_manufacturing_items')
                ->join('simple_manufacturings', 'simple_manufacturings.id', "=", 'simple_manufacturing_items.simple_manufacturing_id')
                ->where('item_id', $request->item_id)
                ->where('type', 2)
                ->select(DB::raw('sum(amount) as qty,simple_manufacturings.date,"manu" as type')), 'simple_manufacturings.date')->groupBy('simple_manufacturings.date')->get();
            $data = array_merge($data, $manufactured->toArray());

            #manufacture wastage qty
            $manufactured = rangeSelector($request, DB::table('simple_manufacturing_items')
                ->join('simple_manufacturings', 'simple_manufacturings.id', "=", 'simple_manufacturing_items.simple_manufacturing_id')
                ->where('item_id', $request->item_id)
                ->where('type', 3)
                ->select(DB::raw('sum(amount) as qty,simple_manufacturings.date,"waste" as type')), 'simple_manufacturings.date')->groupBy('simple_manufacturings.date')->get();
            $data = array_merge($data, $manufactured->toArray());


            #data from sell item
            $sellitemquery = DB::table('sellitems')->select(DB::raw('sum(qty) as qty,date,"sell" as type'))->where('item_id', $request->item_id);
            $sellitems = rangeSelector($request, $sellitemquery)->groupBy('date')->get();
            $data = array_merge($data, $sellitems->toArray());

            #data from billitem
            $billitemquery = DB::table('bill_items')
                ->join('bills', 'bills.id', '=', 'bill_items.bill_id')
                ->select(DB::raw('sum(bill_items.qty) as qty,bills.date,"sell" as type'))->where('bill_items.item_id', $request->item_id);
            $billitems = rangeSelector($request, $billitemquery, 'bills.date')->groupBy('bills.date')->get();
            $data = array_merge($data, $billitems->toArray());

            $managedData = collect($data)->sortBy('date')->groupBy('date');
            return view('admin.item.stock_traking_data',compact('managedData'));
        } else {
            $items = Item::all();
            return view('admin.item.stock_tracking', compact('items'));
        }
    }
}
