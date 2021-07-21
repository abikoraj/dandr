<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index(Request $request){
        return view('admin.counter.index',['counters'=>Counter::all()]);
        
    }

    public function add(Request $request){
        $counter=new Counter();
        $counter->name=$request->name;
        $counter->save();
        return view('admin.counter.single',compact('counter'));
        
    }
    public function update($id,Request $request){
        $counter= Counter::find($id);
        $counter->name=$request->name;
        $counter->save();
        return redirect()->back();
        // return view('admin.counter.single',compact('counter'));
        
    }
}
