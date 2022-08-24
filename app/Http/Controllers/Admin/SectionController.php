<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index()
    {
        $sections=DB::select('select * from sections');
        return view('admin.table.section.index',compact('sections'));
    }

    public function add(Request $request)
    {
        $section=new Section();
        $section->name=$request->name;
        $section->save();
        return redirect()->back();

    }
    public function edit(Request $request)
    {
        DB::update("update sections set name=? where id=?",[$request->name,$request->id]);
        return redirect()->back();
        
    }

    public function del($id,Request $request)
    {
        if(Table::where('section_id',$id)->count()>0){
            return redirect()->back()->with('message','Cannot delete table already added in section');
        }else{
            DB::delete('delete from sections where id=?',[$id]);
            return redirect()->back();
        }
        
    }
}
