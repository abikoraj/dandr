<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()){

        }else{

            return view('admin.accounting.bank.transaction.index');
        }
        
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            $banks=getBanks();
            return view('admin.accounting.bank.transaction.add',compact('banks'));
        }
    }
}
