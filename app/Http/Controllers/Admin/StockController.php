<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $stock_query=DB::table('stocks');
            $stock_query=rangeSelector($request,$stock_query);
            return response()->json($stock_query->orderBy('date','asc')->orderBy('id','asc')->get(['id','date','opening','closing']));
        }else{
            return view('admin.accounting.stock.index');
        }
    }
    public function add(Request $request)
    {
        $stock=new Stock();
        $stock->date=str_replace('-','',$request->date);
        if($request->type==1){
            $stock->opening=$request->amount;
        }else{
            $stock->closing=$request->amount;

        }
        $stock->save();
        return response()->json(['status'=>true]);
    }
    public function del(Request $request)
    {
        DB::delete('delete from stocks where id = ?', [$request->id]);
        return response()->json(['status'=>true]);
    }

}
