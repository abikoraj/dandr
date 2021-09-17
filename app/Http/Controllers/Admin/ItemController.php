<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Item;
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
            return view('admin.item.index',compact('items','large'));
        }
    }

    public function all(){
        $items=Item::select('id','title','number')->where('posonly',1)->get();
        return response()->json($items);
    }
    public function barcode(Request $request){
        $items=Item::where('number','like',$request->keyword.'%')->select('id','sell_price','title','number')->take(24)->get();
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
                    $stock=CenterStock::where('item_id',$id)->where('center_id',$center_id)->first();
                    if($stock==null){
                        $stock=new CenterStock();
                        $stock->item_id=$id;
                        $stock->center_id=$center_id;
                    }
                    $stock->amount=$amount;
                    $stock->rate=$rate;
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
}
