<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Distributer;
use App\Models\DistributerMilk;
use App\Models\DistributerMilkReport;
use App\Models\Ledger;
use App\Models\User;
use Illuminate\Http\Request;

class DistributerMilkController extends Controller
{
    // milk list
    public function index(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $date = str_replace('-', '', $request->date);
            $s = $request->session;
            $data = DistributerMilk::where('date', $date);
            if ($s > 0) {
                $data = $data->where('session', $s);
            }
            $data = $data->join('distributers', 'distributers.id', '=', 'distributer_milks.distributer_id')
                ->join('users', 'distributers.user_id', '=', 'users.id')
                ->select('distributer_milks.*', 'users.name');
            $milkDatas = $data->get();
            return view('admin.distributer.milk.list', compact('milkDatas'));
        } else {
            return view('admin.distributer.milk.index');
        }
    }

    public function add(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $s = $request->session;
        $milkData = new DistributerMilk();
        $milkData->distributer_id = $request->id;
        $milkData->amount = $request->amount;
        $milkData->session = $request->session;
        $milkData->date = $date;
        $milkData->save();
        $user = User::join('distributers', 'distributers.user_id', '=', 'users.id')->select('users.name')->where('distributers.id', $request->id)->first();
        $milkData->name = $user->name;
        return view('admin.distributer.milk.single', compact('milkData'));
    }

    public function update(Request $request)
    {
        $milkData = DistributerMilk::find($request->id);
        $milkData->amount = $request->amount;
        $milkData->save();
        return response('ok');
    }
    public function delete(Request $request)
    {
        $milkData = DistributerMilk::find($request->id);
        $milkData->delete();
        return response('ok');
    }
    public function addToLedger(Request $request){

        
        $report=DistributerMilkReport::where('id',$request->id)->first();
        if($report==null){
            $report=new DistributerMilkReport();
        }
        // dd($request->all());
        $report->snf=$request->snf;
        $report->fat=$request->fat;
        $report->distributer_id=$request->distributer_id;
        $report->rate=$request->rate;
        $report->total=$request->total;
        $report->milk=$request->milk;
        $report->year=$request->year;
        $report->month=$request->month;
        $report->session=$request->session;
        $report->is_fixed=$request->is_fixed;
        // $report->date=$date;
        $report->save();
        $user=User::join('distributers','distributers.user_id','=','users.id')->where('distributers.id',$request->distributer_id)->select('users.*')->first();
        $l=Ledger::where([
            'user_id'=>$user->id,
            'identifire'=>132,
            'foreign_key'=>$report->id
        ])->first();
        $ledger=new LedgerManage($user->id);
        if($l==null){
            $date = str_replace('-', '', $request->date);
            if(env('acc_system','old')=='old'){
                $l=$ledger->addLedger("Milk (".$report->milk."l X ".$report->rate.")",1,$report->total,$date,132,$report->id);
            }else{
                $l=$ledger->addLedger("Milk (".$report->milk."l X ".$report->rate.")",2,$report->total,$date,132,$report->id);
            }
        }else{
            $l->title="Milk (".$report->milk."l X ".$report->rate.")";
            $l->amount=$report->total;
            $l->save();
        }
        response('ok');
    }
    //end milk list

    //snf fat data
    
    //end snf fat data
}
