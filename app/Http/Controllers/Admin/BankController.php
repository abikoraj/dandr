<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index(Account $account)
    {
        $fy = getFiscalYear();
        $banks = DB::select("select * from banks 
            where account_id in (select id from accounts 
            where parent_id= (select id from accounts where identifire='1.2' and fiscal_year_id={$fy->id} limit 1)
        )");
        foreach ($banks as $key => $bank) {
            $bank->balance=getTotal($bank->account_id);
        }
        // dd($banks);
        return view('admin.bank.index', compact('banks','account'));
    }
    public function add(Request $request)
    {

        $bank = new Bank();
        $bank->name = $request->name;
        $bank->address = $request->address;
        $bank->phone = $request->phone;
        $bank->accno = $request->accno;
        // $bank->balance=$request->balance;
        // $bank->account_id=$request->account_id;
        $bank->save();

        $parent = Account::where('id', $request->account_id)->first();
        $fy = DB::table('fiscal_years')->where('id', $parent->fiscal_year_id)->first();
        $identifire = $parent->identifire . "." . $bank->id;
        $account = new Account();
        $account->identifire = $identifire;
        $account->parent_id = $parent->id;
        $account->name = $request->name;
        $account->type = $parent->type;
        // $account->amount = $request->amount;
        $account->fiscal_year_id = $fy->id;
        $account->save();

        $bank->account_id=$account->id;
        $bank->save();


        return view('admin.bank.single', compact('bank'));
    }
    public function update(Request $request)
    {
        $bank = Bank::find($request->id);
        $bank->name = $request->name;
        $bank->address = $request->address;
        $bank->phone = $request->phone;
        $bank->accno = $request->accno;
        // $bank->balance=$request->balance;
        $bank->save();

        $account=Account::where('id',$bank->account_id)->first();
        $account->name=$bank->name;
        $account->save();
        return redirect()->back();
    }
    public function delete(Request $request)
    {
        $bank = Bank::find($request->id);
        DB::table('acounts')->where('id',$bank->id)->delete();
        $bank->delete();
        return response('ok');
    }
}
