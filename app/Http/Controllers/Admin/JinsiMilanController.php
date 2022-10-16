<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\JinsiMilan;
use App\Models\Ledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JinsiMilanController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $query=DB::table('jinsi_milans');
            $query=rangeSelector($request,$query,'date');
            $jinsiMilans=$query->select(DB::raw('id,amount,date,from_user_id,to_user_id,(select name from users where id=from_user_id limit 1) as fromParty,(select name from users where id=to_user_id limit 1) as toParty'))->get();
            return response()->json($jinsiMilans);
        }else{
            return view('admin.jinsimilan.index');
        }
    }

    public function del(Request $request)
    {
        DB::delete('delete from jinsi_milans where id=?',[$request->id]);
        Ledger::where('foreign_key',$request->id)->whereIn('identifire',[408,409])->delete();
    }
    
    public function add(Request $request){

        if(isGET()){
            $users=getUserBalance(['customers','distributers','suppliers','farmers']);
            return view('admin.jinsimilan.add',compact('users'));

        }else{
            $date=getNepaliDate($request->date);
            $jinsiMilan=new JinsiMilan();
            $jinsiMilan->date=$date;
            $jinsiMilan->amount=$request->amount;
            $jinsiMilan->from_user_id=$request->from_user_id;
            $jinsiMilan->to_user_id=$request->to_user_id;
            $jinsiMilan->detail=$request->detail;
            $jinsiMilan->save();
            $lFrom=new LedgerManage($jinsiMilan->from_user_id);
            $lTo=new LedgerManage($jinsiMilan->to_user_id);
            $lFrom->addLedger('Jinsi Milan To '.$lTo->user->name,1,$jinsiMilan->amount,$date,408,$jinsiMilan->id);
            $lTo->addLedger('Jinsi Milan From '.$lFrom->user->name,2,$jinsiMilan->amount,$date,409,$jinsiMilan->id);
            return response()->json(['status'=>true]);
        }
        
    }

    public function edit(Request $request,$id){

        $jinsiMilan=JinsiMilan::where('id',$id)->first();
        $jinsiMilan->fromParty=DB::selectOne('select name from users where id=?',[$jinsiMilan->from_user_id])->name;
        $jinsiMilan->toParty=DB::selectOne('select name from users where id=?',[$jinsiMilan->to_user_id])->name;
        if(isGET()){
            return view('admin.jinsimilan.edit',compact('jinsiMilan'));

        }else{
            $date=getNepaliDate($request->date);
            $jinsiMilan=new JinsiMilan();
            $jinsiMilan->amount=$request->amount;
            $jinsiMilan->detail=$request->detail;
            $jinsiMilan->save();
     
            $fromLedger=Ledger::where('foreign_key',$id)->where('identifire',408)->first();
            $fromLedger->amount=$jinsiMilan->amount;
            $fromLedger->save();
            $toLedger=Ledger::where('foreign_key',$id)->where('identifire',409)->first();
            $toLedger->amount=$jinsiMilan->amount;
            $toLedger->save();
        }
        
    }
}
