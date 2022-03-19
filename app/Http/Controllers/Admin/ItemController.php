<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\StockOut;
use App\Models\StockOutItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //
    public function index(Request $request){
        $items=[];
        if($request->getMethod()=="POST"){
            $query=Item::where('id','>',0);
            $query1=Item::where('id','>',0);
            $step=$request->step??0;
            $countStep=env('countstep',24);
            $data=[];
            $data['page']=$step;
            if($request->filled('keyword')){
                $query=$query->where('title','like',$request->keyword.'%');
                $query1=$query1->where('title','like',$request->keyword.'%');
                $data['key']=$request->keyword;

            }
            // $temp=$query;
            if($step==0){
                $query=$query->take($countStep);
            }else{
                $query=$query->skip($step*$countStep)->take($countStep);
            }
            $items=$query->select('id','title','sell_price','stock','unit','reward_percentage','number')->latest()->get();
            $data['total']=$query1->count();
            $data['items']=$items;

            return response()->json($data);
        }else{

            $large=env('large',false);
            if(!$large){

                $items = Item::select('id','title','sell_price','stock','unit','reward_percentage','number')->latest()->get();
            }
            $centers=DB::table('centers')->get(['id','name']);
            return view('admin.item.index',compact('items','large','centers'));
        }
    }

    public function all(){
        $items=Item::select('id','title','number')->where('posonly',1)->get();
        return response()->json($items);
    }
    public function barcode(Request $request){
        if($request->filled('exact')){
            $items=DB::table('items')->where('number',$request->keyword)->select('id','sell_price','title','number')->take(1)->get();
        }else{

            $items=Item::where('number','like',$request->keyword.'%')->select('id','sell_price','title','number')->take(24)->get();
        }
        return response()->json($items);
    }

    public function product(Request $request){
        $items=Item::where('title','like',$request->keyword.'%')->where('disonly',1)->select('id','dis_price','title','dis_number','sell_price','number')->take(24)->get();
        return response()->json($items);
    }

    public function productBarcode(Request $request){
        $items=Item::where('dis_number','like',$request->keyword.'%')->where('disonly',1)->select('id','dis_price','title','dis_number','sell_price','number')->take(24)->get();
        return response()->json($items);
    }


    public function save(Request $request){
        $item = new Item();
        $item->title = $request->name;
        $item->number = $request->number;
        $item->cost_price = $request->cost_price;
        $item->sell_price = $request->sell_price;
        $item->stock = $request->stock;
        $item->unit = $request->unit;
        $item->wholesale = $request->wholesale??0;
        $item->reward_percentage = $request->reward;
        if($request->hasFile('image')){
            $item->image=$request->image->store('uploads/item');
        }
        $item->trackstock=$request->trackstock??0;
        $item->trackexpiry=$request->trackexpiry??0;
        $item->sellonline=$request->sellonline??0;
        $item->disonly=$request->disonly??0;
        $item->posonly=$request->posonly??0;
        $item->farmeronly=$request->farmeronly??0;

        $item->taxable=$request->taxable??0;
        $item->tax=$request->tax;

        $item->description=$request->description;
        $item->minqty=$request->minqty;
        $item->expirydays=$request->expirydays;
        $item->dis_number=$request->dis_number;
        $item->dis_price=$request->dis_price;

        $item->save();

        if(env('multi_stock',false)){
            if($request->filled('centers')){
                foreach ($request->centers as $key => $center_id) {
                    $amount=$request->input('qty_'.$center_id);
                    $rate=$request->input('rate_'.$center_id);
                    $stock=new CenterStock();
                    $stock->item_id=$item->id;
                    $stock->center_id=$center_id;
                    $wholesale=$request->input('wholesale_'.$center_id);
                    $stock->amount=$amount;
                    $stock->rate=$rate;
                    $stock->wholesale=$wholesale;
                    $stock->save();
                }
            }
        }
        if($request->filled('rettype')){
            return response()->json($item);
        }else{

            return view('admin.item.single',compact('item'));
        }
    }

    public function edit(Request $request){
        $item=Item::where('id',$request->id)->first();
        // dd($item);
        return view('admin.item.edit',compact('item'));
    }

    public function update(Request $request){
        $item = Item::where('id',$request->id)->first();
        $item->title = $request->name;
        $item->number = $request->number;
        $item->cost_price = $request->cost_price;
        $item->sell_price = $request->sell_price;
        $item->stock = $request->stock;
        $item->unit = $request->unit;
        $item->reward_percentage = $request->reward;

        if($request->hasFile('image')){
            $item->image=$request->image->store('uploads/item');
        }

        $item->trackstock=$request->trackstock??0;
        $item->trackexpiry=$request->trackexpiry??0;
        $item->sellonline=$request->sellonline??0;
        $item->disonly=$request->disonly??0;
        $item->posonly=$request->posonly??0;
        $item->farmeronly=$request->farmeronly??0;

        $item->taxable=$request->taxable??0;
        $item->tax=$request->tax;

        $item->description=$request->description;
        $item->minqty=$request->minqty;
        $item->expirydays=$request->expirydays;
        $item->dis_number=$request->dis_number;
        $item->dis_price=$request->dis_price;

        $item->save();

        return view('admin.item.single',compact('item'));
    }

    public function delete($id){
        $item = Item::find($id);
        $item->delete();
    }

    public function centerStock($id,Request $request){
        if(env('multi_stock',false)){

            // $totalStock=0;
            $item = Item::find($id);
            if($request->getMethod()=="POST"){
                foreach ($request->center_ids as $center_id) {
                    $amount=$request->input('amount_'.$center_id);
                    $rate=$request->input('rate_'.$center_id);
                    $wholesale=$request->input('wholesale_'.$center_id);
                    $stock=CenterStock::where('item_id',$id)->where('center_id',$center_id)->first();
                    if($stock==null){
                        $stock=new CenterStock();
                        $stock->item_id=$id;
                        $stock->center_id=$center_id;
                    }
                    $stock->amount=$amount;
                    $stock->rate=$rate;
                    $stock->wholesale=$wholesale;
                    $stock->save();
                    // $totalStock+=$amount;
                }
                // $item->stock=$totalStock;
                // $item->save();
                return redirect()->back()->with('msg','Stock Updated Sucessfully');
            }else{
                $centers=Center::select('id','name')->get();
                return view('admin.item.stock',compact('item','centers'));
            }
        }else{
            return redirect()->route('admin.dashboard');
        }

    }


    public function stockOut(Request $request)
    {
        $items=[];
        if($request->getMethod()=="GET"){
            $centers=DB::table('centers')->get(['id','name']);
            $items = DB::table('items')->get(['id','title','number']);

            return view('admin.item.stockout',compact('items','centers'));
        }else{
            $sout=new StockOut();
            $date = str_replace('-', '', $request->info['date']);
            $center_id = str_replace('-', '', $request->info['center_id']);
            // dd($request->items);
            $ids=[];
            try {
                //code...
                $sout=StockOut::create([
                    'date'=>$date,
                    'center_id'=>$center_id
                ]);
                foreach ($request->items as $key => $item) {
                    $sout_item=StockOutItem::create([
                        'item_id'=>$item['item_id'],
                        'amount'=>$item['qty'],
                        'stock_out_id'=>$sout->id
                    ]);
                    array_push($ids,$sout_item->id);

                    $cstock=CenterStock::where('item_id',$sout_item->item_id,$center_id)->first();
                    if($cstock==null){

                        $item=DB::table('items')->where('id',$sout_item->item_id)->first(['sell_price','wholesale']);
                        $cstock=new CenterStock();
                        $cstock->item_id=$sout_item->item_id;
                        $cstock->center_id=$center_id;
                        $cstock->amount=$sout_item->amount;
                        $cstock->rate=$item->sell_price;
                        $cstock->wholesale=$item->wholesale;

                    }else{
                        $cstock->amount+=$sout_item->amount;

                    }
                    $cstock->save();
                }

                return response('ok');
            } catch (\Throwable $th) {
                if($sout->id==null || $sout->id==0){
                    $sout->delete();
                }

                if(count($ids)>0){
                    StockOutItem::whereIn('id',$ids)->delete();
                }
                return abort(500,$th->getMessage());


            }
        }
    }

    public function stockOutList(){
        $stockOuts=DB::table('stock_outs')->join('centers','centers.id','=','stock_outs.center_id')->select(
            'stock_outs.date',
            'stock_outs.id',
            'stock_outs.canceled',
            'centers.name'
        )->orderBy('stock_outs.id','desc')->get();
        // dd($stockOuts);
            return view('admin.item.stockoutlist',compact('stockOuts'));
    }

    public function stockOutView($id)
    {
        $stockOut=DB::table('stock_outs')->join('centers','centers.id','=','stock_outs.center_id')->select(
            'stock_outs.date',
            'stock_outs.id',
            'stock_outs.canceled',
            'centers.name'
        )->where('stock_outs.id',$id)->first();
        $stockOutItems=DB::table('stock_out_items')
        ->join('items','items.id','=','stock_out_items.item_id')
        ->where('stock_out_items.stock_out_id',$id)
        ->get(['stock_out_items.amount','items.title','stock_out_items.id']);
        return view('admin.item.stockoutview',compact ('stockOut','stockOutItems','id'));
    }
    public function stockOutCancel($id)
    {
        DB::table('stock_outs')->where('stock_outs.id',$id)->update(['canceled'=>1]);
        $stockOut=DB::table('stock_outs')->where('id',$id)->first();
        $stockOutItems=DB::table('stock_out_items')
        ->where('stock_out_id',$id)->get(['item_id','amount']);
        foreach ($stockOutItems as $key => $item) {
            $cstock=CenterStock::where('item_id',$item->item_id,$stockOut->center_id)->first();
            if($cstock!=null){
                $cstock->amount-=$item->amount;
                $cstock->save();
            }
        }
    }
}
