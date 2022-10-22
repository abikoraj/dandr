<?php

namespace App\Http\Controllers;

use App\LedgerManage;
use App\Models\Ledger;
use App\Models\Reconciliation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReconciliationController extends Controller
{
    public function index(){
        $users = DB::table('farmers')->join('users','users.id','=','farmers.user_id')->select('farmers.user_id','users.name')->get();
        return view('admin.reconciliation.index',compact('users'));
    }

    public function add(Request $request){
        // dd($request->all());
        $date=getNepaliDate($request->date);

        $data = new Reconciliation();
        $data->date = $date;
        $data->user_id = $request->farmer_user_id;
        $data->amount = $request->amount;
        $data->type = $request->type;
        $data->save();
        return response()->json(['status'=>true]);

        $ledger=new LedgerManage($data->farmer_user_id);
        $ledger->addLedger('Reconciliation statement',$request->type,$data->amount,$date,701,$data->id);

        return response()->json(['status'=>true]);

    }

    public function list(){
        $datas = DB::table('reconciliations')
        ->join('users','users.id','=','reconciliations.user_id')
        ->select('reconciliations.*','users.name')->get();
        return view('admin.reconciliation.data',compact('datas'));
    }

    public function update(Request $request){
        $date=getNepaliDate($request->date);
        $data = Reconciliation::where('id',$request->id)->first();
        $ledger = Ledger::where('identifire',701)->where('foreign_key',$request->id)->first();
        $data->date = $date;
        $data->user_id = $request->farmer_user_id;
        $data->amount = $request->amount;
        $data->type = $request->type;
        $data->save();

        $ledger->amount = $data->amount;
        $ledger->type = $data->type;
        $ledger->save();
        return response()->json(['status'=>true]);
    }

    public function delete(Request $request){
        // dd($request->all());
        $data = Reconciliation::where('id',$request->id)->first();
        $ledger = Ledger::where('identifire',701)->where('foreign_key',$request->id)->first();
        $ledger->delete();
        $data->delete();
        return response()->json(['status'=>true]);
    }
}
