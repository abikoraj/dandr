<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TableController extends Controller
{
    public function index()
    {
        $tables=DB::table('tables')->get();
        // dd($tables);
        $sections=DB::table('sections')->get();

        return view('admin.table.index',compact('tables','sections'));
    }


    public function add(Request $request)
    {
        $table=new Table();
        $table->name=$request->name;
        $table->section_id=$request->section_id;
        $table->save();
        return redirect()->back();

    }
    public function edit(Request $request)
    {
        DB::update("update tables set name=? where id=?",[$request->name,$request->id]);
        return redirect()->back();
        
    }

    public function del($id,Request $request)
    {
       
            DB::delete('delete from tables where id=?',[$id]);
            return redirect()->back();
    
        
    }

}
