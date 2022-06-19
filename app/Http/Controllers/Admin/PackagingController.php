<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Repackage;
use App\Models\RepackageItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackagingController extends Controller
{
    //
    public function index(Request $request )
    {
        if($request->getMethod()=="POST"){

        }else{
            $repackages=DB::select('select id,date,canceled from repackages');
            $repackageItems=DB::select('select repackage_id,count(*) as itemCount from repackage_items group by repackage_id');
            $centers=DB::select('select id,name from centers');
            return view('admin.item.packaging.index',compact('repackages','repackageItems','centers'));
        }
    }

    public function view($id)
    {

        $repackage=DB::selectOne('select id,date,center_id,canceled from repackages where id=?',[$id]);
        $repackageItems=DB::select('select ri.id,ri.from_amount,ri.to_amount,
        fi.title as from_item,
        ti.title as to_item
        from repackage_items ri
        join items fi on ri.from_item_id=fi.id
        join items ti on ri.to_item_id=ti.id
        where ri.repackage_id=?
        ',[$id]);
        return view('admin.item.packaging.view',compact('repackage','repackageItems'));
    }

    public function add(Request $request){
        if($request->getMethod()=="POST"){
            $date=str_replace('-','',$request->date);
            $center_id=$request->center_id;
            $datas=$request->datas;
            $repackage=new Repackage();
            $repackage->date=$date;
            if(env('multi_stock',false)){

                $repackage->center_id=$center_id;
            }
            $repackage->save();
            foreach ($datas as $key => $data) {
                $repackageItem=new RepackageItem();
                $repackageItem->from_item_id=$data['from_item_id'];
                $repackageItem->to_item_id=$data['to_item_id'];
                $repackageItem->from_amount=$data['from_amount'];
                $repackageItem->to_amount=$data['to_amount'];
                $repackageItem->repackage_id=$repackage->id;
                $repackageItem->save();
                maintainStock($repackageItem->from_item_id,$repackageItem->from_amount,$center_id,'out');
                maintainStock($repackageItem->to_item_id,$repackageItem->to_amount,$center_id,'in');
            }
            return response()->json(['status'=>true]);
        }else{
            $items=DB::select('select id,title,number,conversion_id from items');
            $centers=DB::select('select id,name from centers');
            if(env('multi_package',false)){
                $units=DB::select('select * from conversions');
                return view('admin.item.packaging.addmulti',compact('items','units','centers'));
            }else{
                return view('admin.item.packaging.add',compact('items','centers'));
            }
        }
    }

    public function cancel($id)
    {
        $repackage=Repackage::where('id',$id)->first();
        if($repackage->canceled==1){
            return redirect()->back();
        }
        $repackageItems=RepackageItem::where('repackage_id',$id)->get();
        foreach ($repackageItems as $key => $repackageItem) {
            maintainStock($repackageItem->from_item_id,$repackageItem->from_amount,$repackage->center_id,'in');
            maintainStock($repackageItem->to_item_id,$repackageItem->to_amount,$repackage->center_id,'out');
        }
        $repackage->canceled=1;
        $repackage->save();
        return redirect()->back();
    }
}
