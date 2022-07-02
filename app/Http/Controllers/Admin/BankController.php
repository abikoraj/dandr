<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index(Account $account){

        return view('admin.bank.index',['banks'=>DB::table('banks')->where('account_id',$account->id)->get(),'account'=>$account]);
    }
    public function add(Request $request){
        $bank=new Bank();
        $bank->name=$request->name;
        $bank->address=$request->address;
        $bank->phone=$request->phone;
        $bank->accno=$request->accno;
        $bank->balance=$request->balance;
        $bank->account_id=$request->account_id;
        $bank->save();
        return view('admin.bank.single',compact('bank'));
    }
    public function update(Request $request){
        $bank= Bank::find($request->id);
        $bank->name=$request->name;
        $bank->address=$request->address;
        $bank->phone=$request->phone;
        $bank->accno=$request->accno;
        $bank->balance=$request->balance;
        $bank->save();
        return redirect()->back();
    }
    public function delete(Request $request){
        $bank= Bank::find($request->id);
        $bank->delete();
        return response('ok');
    }
}
