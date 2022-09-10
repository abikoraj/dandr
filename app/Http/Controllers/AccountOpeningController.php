<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountOpeningController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            $fy=DB::table('fiscal_years')->where('name',env('fiscal_year'))->first();
            $accounts=DB::table('accounts')->where('fiscal_year_id',$fy->id)->get(['name','id']);
            $openings=DB::table('account_ledgers')->where('fiscal_year_id',$fy->id)->where('identifier','901')->get();
            return view('admin.accounting.opening.index',compact('accounts','openings','fy'));
        }
    }
}
