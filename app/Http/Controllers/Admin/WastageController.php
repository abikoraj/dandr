<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManufactureWastage;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WastageController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){

            $wastages_query=DB::table('manufacture_wastages as mw')
            ->join('items as i','i.id','=','mw.item_id')
            ->join('centers as c','c.id','=','mw.center_id');

            $type=$request->type;
            $year=$request->year;
            $month=$request->month;
            $session=$request->session;
            $week=$request->week;
            $range=[];
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $wastages_query = $wastages_query->where('mw.date', '>=', $range[1])->where('mw.date', '<=', $range[2]);

            } elseif ($type == 1) {
                $date = str_replace('-', '', $request->date1);
                $wastages_query = $wastages_query->where('mw.date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $wastages_query = $wastages_query->where('mw.date', '>=', $range[1])->where('mw.date', '<=', $range[2]);

            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $wastages_query = $wastages_query->where('mw.date', '>=', $range[1])->where('mw.date', '<=', $range[2]);

            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $wastages_query = $wastages_query->where('mw.date', '>=', $range[1])->where('mw.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $wastages_query = $wastages_query->where('mw.date', '>=', $range[1])->where('mw.date', '<=', $range[2]);

            } elseif ($type == 6) {
                $fy=DB::selectOne('select startdate,enddate from fiscal_years where id=?',[$request->fiscalyear]);
                $range[1] = $fy->startdate;
                $range[2] = $fy->enddate;
                $wastages_query = $wastages_query->where('mw.date', '>=', $range[1])->where('mw.date', '<=', $range[2]);
            }

            if($request->center_id>=0){
                $wastages_query=$wastages_query->where('mw.center_id',$request->center_id);
            }
            $manufacturingWastages=collect([]);
            if($request->filled('showManufacture')){
                $wastages_query1=clone $wastages_query;
                $manufacturingWastages=$wastages_query1
                ->join('manufacture_processes as mp','mp.id','=','mw.manufacture_process_id')
                ->where('mp.stage','<',4)
                ->select(DB::raw('mw.id,mw.date,mw.amount,mw.rate,i.title,c.name as center,manufacture_process_id'))->get();
            }
            $wastages=$wastages_query->select(DB::raw('mw.id,mw.date,mw.amount,mw.rate,i.title,c.name as center,manufacture_process_id'))->whereNull('manufacture_process_id')->get();
            $wastages=$wastages->merge($manufacturingWastages);
           return response(json_encode($wastages,JSON_NUMERIC_CHECK));
        }else{
            $centers=DB::select('select id,name  from centers');
            $items=DB::select('select id,title  from items');
            return view('admin.wastage.index',compact('centers','items'));
        }
    }

    public function add(Request $request)
    {
        $item=DB::selectOne('select title,cost_price,sell_price from items where id=?',[$request->item_id]);
        $wastage=new ManufactureWastage();
        $wastage->item_id=$request->item_id;
        $wastage->amount=$request->amount;
        $wastage->center_id=$request->center_id;
        $wastage->date=str_replace('-','',$request->date);
        $wastage->rate=$item->sell_price==0?$item->cost_price:$item->sell_price;
        $wastage->save();
        maintainStock($request->item_id,$request->amount,$request->center_id,'out');
        $wastage->title=$item->title;
        return response()->json($wastage);
    }
    public function del(Request $request)
    {
        $wastage=ManufactureWastage::where('id',$request->id)->first();
        maintainStock($wastage->item_id,$wastage->amount,$wastage->center_id,'in');
        $wastage->delete();

        return response()->json(['status'=>true]);
    }
}
