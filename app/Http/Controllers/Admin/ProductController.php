<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductPurchaseItem;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class ProductController extends Controller
{
    public function index(){
        $products=Product::all();
        return view('admin.products.index',compact('products'));
    }

    public function add(Request $request){
        $product=new Product();
        $product->name=$request->name;
        $product->price=$request->price;
        $product->unit=$request->unit;
        $product->image='';
        $product->minqty=0;
        $product->desc='';
        $product->stock= $request->stock;
        $product->save();
        return view('admin.products.single',compact('product'));
    }

    public function update(Request $request){
        $product=Product::find($request->id);
        $product->name=$request->name;
        $product->price=$request->price;
        $product->unit=$request->unit;
        $product->image='';
        $product->minqty=0;
        $product->desc='';
        $product->stock=$request->stock;
        $product->save();
        return response('ok');
    }

    public function del(Request $request){
        $product=Product::find($request->id);
        $product->delete();
        return response('ok');

    }


    // product purchase
    public function productPurchase(){
        return view('admin.products.purchase.index');
    }

    public function productPurchaseStore(Request $request){
        // dd($request->all());
        $date = str_replace('-','',$request->date);
        $purchase = new ProductPurchase();
        $purchase->billno = $request->billno;
        $purchase->total = $request->gtotal;
        $purchase->date = $date;
        $purchase->save();
        $traker = explode(',',$request->counter);

        foreach ($traker as  $value) {
            $item = new ProductPurchaseItem();
            $item->product_purchase_id = $purchase->id;
            // dd($request->input("productid_".$value));
            $item->title = $request->input('productname_'.$value);
            $item->rate = $request->input('rate_'.$value);
            $item->product_id = $request->input("productid_".$value);
            $item->qty = $request->input('qty_'.$value);
            $item->save();
                $stock = Product::where('id',$request->input("productid_".$value))->first();
                $stock->stock += $request->input('qty_'.$value);
                $stock->save();
        }
        return redirect()->back()->with('message','Purchase item added successfully !');


    }

}
