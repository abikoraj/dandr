<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manufacture;
use App\Models\Manufactureitem;
use App\Models\Product;
use Illuminate\Http\Request;

class ManufactureController extends Controller
{
    public function index(){
        return view('admin.manufacture.index');
    }

    public function store(Request $request){
        // dd($request->all());
        $date = str_replace('-','',$request->date);
        // dd($date);
        $manu = new Manufacture();
        $manu->date = $date;
        $manu->product_id = $request->item_id;
        $manu->qty = $request->m_qty;
        $manu->save();
        $s = Product::where('id',$request->item_id)->first();
        $s->stock += $request->m_qty;
        $s->save();
        $traker = explode(',',$request->counter);

        foreach ($traker as  $value) {
            $item = new Manufactureitem();
            $item->manufacture_id = $manu->id;
            // dd($request->input("productid_".$value));
            $item->product_id = $request->input("productid_".$value);
            $item->req_qty = $request->input('reqQty_'.$value);
            $item->save();
                $stock = Product::where('id',$request->input("productid_".$value))->first();
                $stock->stock -= $request->input('reqQty_'.$value);
                $stock->save();
        }
        return redirect()->back()->with('message','Manufacture item added successfully !');
    }


    public function list(){
        $manu = Manufacture::latest()->get();
        return view('admin.manufacture.list',compact('manu'));
    }
}
