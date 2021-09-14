<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request){
        $cats=[];
        if($request->filled('cat_id')){
            $cats=Category::where('parent_id',$request->cat_id)->get();
        }else{
            $cats=Category::where('parent_id',-1)->get();
        }
        return view('admin.category.index',['cats']);
    }
}
