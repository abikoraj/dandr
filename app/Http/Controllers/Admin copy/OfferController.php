<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Offer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        if ($request->getMethod() == "POST") {
        } else {

            $offer1 = Offer::where('active', 1)->get();
            $offer0 = Offer::where('active', 0)->get();
            return view('admin.offer.index', compact('offer1', 'offer0'));
        }
    }
    public function add(Request $request)
    {
        $offer = new Offer();
        $offer->name = $request->name;
        $offer->type = $request->type;
        $offer->start_date = str_replace("-", "", $request->start_date);
        $offer->end_date = str_replace("-", "", $request->end_date);
        $offer->active = 1;
        $offer->save();
        return redirect()->back();
    }
    public function update(Request $request)
    {
        $offer = Offer::find($request->id);
        $offer->name = $request->name;
        $offer->type = $request->type;

        $offer->start_date = str_replace("-", "", $request->start_date);
        $offer->end_date = str_replace("-", "", $request->end_date);
        $offer->save();
        return redirect()->back();
    }
    public function del(Offer $offer)
    {
        if ($offer->active == 0) {
            $offer->delete();
        } else {
            $offer->active = 0;
            $offer->save();
        }
        return redirect()->back();
    }
    public function activate(Offer $offer)
    {

        $offer->active = 1;
        $offer->save();

        return redirect()->back();
    }
    public function detail(Offer $offer)
    {
        $large=env('large',false);
        $items=[];
        if(!$large){
            $items=Item::select(DB::raw( 'id ,title,number,(select count(*) as c from offer_items where item_id=items.id) as cc'))->get()->where('cc',0);
        }
        return view('admin.offer.detail.index',compact('offer','items','large'));
    }

    public function getItems(Request $request){
        $items=Item::where('id','>','0');
       

        if($request->filled('barcode')){    
            $items=$items->where('no',$request->barcode);
        }
        if($request->filled('keyword')){
            $items=$items->where('title','like',$request->keyword.'%');
        }

        if($request->filled('step')){
            $step=$request->step;
            if($step==0)
            {
                $items=$items->take(24);
            }else{
                $items=$items->skip($step*24)->take(24);
            }
        }

        $items_arr=$items->select(DB::raw( 'id ,title,number,(select count(*) as c from offer_items where item_id=items.id) as cc'))->get()->where('cc',0);
        // $items_arr=$items->select('id','title','number')->get();
        // dd($items_arr);

        return view('admin.offer.detail.list',['items'=>$items_arr]);
        
    }

    public function addOfferItem(Request $request){
        
    }
}
