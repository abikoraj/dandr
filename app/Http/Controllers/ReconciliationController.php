<?php

namespace App\Http\Controllers;

use App\LedgerManage;
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
        if($request->type == 1){
            $ledger->addLedger('Reconciliation statement',1,$data->amount,$date,411,$data->id);
        }else{
            $ledger->addLedger('Reconciliation statement',2,$data->amount,$date,412,$data->id);
        }
        return response()->json(['status'=>true]);

    }
}
