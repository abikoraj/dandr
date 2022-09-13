<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class ItemCategoryController extends Controller
{
    public function index(Item $item)
    {
        $cats=DB::table('item_categories')->where('item_id',$item->id)->get();
        return view('admin.item.category.index',compact('item','cats'));
    }

    public function update(Request $request,$id){
        $category=ItemCategory::where('id',$id)->first();
        $category->name=$request->name;
        $category->price=$request->price;
        $category->item_id=$id;
        $category->save();
    }
    public function add(Request $request,$id){
        if($request->getMethod()=="POST"){
            $category=new ItemCategory();
            $category->name=$request->name;
            $category->price=$request->price;
            $category->item_id=$id;
            $category->save();
            return view('admin.item.category.single',compact('category'));

        }else{
            return view('admin.item.category.add',compact('id'));
        }
    }

    public function del(Request $request,$id){
        $used=DB::table('bill_items')->where('item_category_id',$id)->count()>0;
        if($used){
            return response()->json(['status'=>false,'message'=>'Cannot delete item category already in use.','id'=>$id]);

        }else{
            DB::table('item_categories')->where('id',$id)->delete();
            return response()->json(['status'=>true,'message'=>'Item category deleted sucessfully','id'=>$id]);
        }
    }
}
