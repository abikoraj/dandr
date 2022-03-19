<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index(){
        return view('admin.bank.index',['banks'=>Bank::all()]);
    }
    public function add(Request $request){
        $bank=new Bank();
        $bank->name=$request->name;
        $bank->address=$request->address;
        $bank->phone=$request->phone;
        $bank->accno=$request->accno;
        $bank->save();
        return view('admin.bank.single',compact('bank'));
    }
    public function update(Request $request){
        $bank= Bank::find($request->id);
        $bank->name=$request->name;
        $bank->address=$request->address;
        $bank->phone=$request->phone;
        $bank->accno=$request->accno;
        $bank->save();
        return redirect()->back();
    }
    public function delete(Request $request){
        $bank= Bank::find($request->id);
        $bank->delete();
        return response('ok');
    }
}
