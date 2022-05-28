<?php

namespace App\Http\Controllers\Admin\Manufacture;

use App\Http\Controllers\Controller;
use App\Models\ManufacturedProduct;
use App\Models\ManufacturedProductItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufactreProductController extends Controller
{
    public function index()
    {
        $items=DB::select('select id,title,conversion_id from items');
        $products=DB::select('select p.*,i.title from manufactured_products p join items i on i.id=p.item_id');
        // $units=DB::select('select id,name,local,main,parent_id,is_base from conversions');
        return view('admin.manufacture.product.index',compact('items','products'));
    }

    public function add(Request $request){
        $product=new ManufacturedProduct();
        $product->item_id=$request->item_id;
        $product->expairy_days=$request->expairy_days;
        $product->day=$request->day;
        $product->hour=$request->hour;
        $product->minute=$request->minute;
        $product->save();
        return response()->json($product);
    }

    public function del(Request $request){

        DB::table('manufactured_products')->where('id',$request->id)->delete();
        return response('ok');
    }

    public function templateIndex($id)
    {
        $product=DB::table('manufactured_products')
        ->join('items','items.id','=','manufactured_products.item_id');
        $manufaturedProductItems=DB::table('manufactured_product_items')
        ->join('items','items.id','=','manufactured_product_items.item_id');
        if(env('multi_package',false)){

            $product=$product->join('conversions','conversions.id','=','items.conversion_id');
            $items=DB::select('select i.id,i.title,c.name as unit from items i join conversions c on i.conversion_id=c.id');
            $manufaturedProductItems=$manufaturedProductItems->join('conversions','conversions.id','=','items.conversion_id');
        }else{
            $items=DB::select('select i.id,i.title,i.unit as unit from items i');
        }
        $product=$product->where('manufactured_products.id',$id)
        ->select(DB::raw( 'items.title,manufactured_products.id,'.(env('multi_package',false)?'conversions.name as unit':'items.unit')))->first();

        $manufaturedProductItems=$manufaturedProductItems->where('manufactured_product_items.manufactured_product_id',$id)
        ->select(DB::raw( 'concat(items.title,\'(\','.(env('multi_package',false)?'conversions.name ':'items.unit').',\')\') as title,manufactured_product_items.amount,manufactured_product_items.id'))
        ->get();
        return view('admin.manufacture.product.template',compact('product','items','manufaturedProductItems'));

    }
    public function templateAdd($id,Request $request)
    {
        $ManufacturedProductItem= new ManufacturedProductItem();
        $ManufacturedProductItem->manufactured_product_id=$id;
        $ManufacturedProductItem->item_id=$request->item_id;
        $ManufacturedProductItem->amount=$request->amount;
        $ManufacturedProductItem->save();
        return response()->json($ManufacturedProductItem);

    }
    public function templateUpdate($id,Request $request)
    {
       DB::table('manufactured_product_items')->where('id',$id)->update([
           'amount'=>$request->amount
       ]);
       return response('ok');

    }
    public function templateDel(Request $request)
    {
       DB::table('manufactured_product_items')->where('id',$request->id)->delete();
       return response('ok');

    }
}
