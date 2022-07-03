<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FixedAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixedAssetController extends Controller
{
    public function index(Account $account)
    {
        $fixedAssets=DB::table('fixed_assets')->where('account_id',$account->id)->get();
        return view('admin.accounting.accounts.fixedassets.index',compact('fixedAssets','account'));
    }
    public function add(Request $request)
    {
        $account=Account::where('id',$request->account_id)->first();
        if($account==null){
            throw new \Exception("Fixed Asset Acc not found ", 1);
        }
        $startdate=(int)(str_replace('-','',$request->startdate));
        if($startdate< $account->fiscalyear->startdate || $startdate>$account->fiscalyear->enddate){
            throw new \Exception("Depreciation start date not in range", 1);
        }

        $fixedAsset=new FixedAsset();
        $fixedAsset->name=$request->name;
        $fixedAsset->depreciation=$request->depreciation;
        $fixedAsset->startdate=$startdate;
        $fixedAsset->amount=$request->amount;
        $fixedAsset->full_amount=$request->full_amount;
        $fixedAsset->account_id=$request->account_id;
        $fixedAsset->save();
        return view('admin.accounting.accounts.fixedassets.single',compact('fixedAsset'));
    }

    public function del(Request $request)
    {
        DB::table('fixed_assets')->where('id',$request->id)->delete();
        return response()->json(['status'=>true]);
    }

    public function update(Request $request,$id){
        if($request->getMethod()=="POST"){
            $fixedAsset=FixedAsset::where('id',$id)->first();
            $account=Account::where('id',$fixedAsset->account_id)->first();
            if($account==null){
                throw new \Exception("Fixed Asset Acc not found ", 1);
            }
            $startdate=(int)(str_replace('-','',$request->startdate));
            if($startdate< $account->fiscalyear->startdate || $startdate>$account->fiscalyear->enddate){
                throw new \Exception("Depreciation start date not in range", 1);
            }

            $fixedAsset->name=$request->name;
            $fixedAsset->depreciation=$request->depreciation;
            $fixedAsset->startdate=$startdate;
            $fixedAsset->amount=$request->amount;
            $fixedAsset->full_amount=$request->full_amount;
            $fixedAsset->save();
            return view('admin.accounting.accounts.fixedassets.single',compact('fixedAsset'));

        }else{
            $fixedAsset=DB::table('fixed_assets')->where('id',$id)->first();
            return view('admin.accounting.accounts.fixedassets.edit',compact('fixedAsset','id'));
        }
    }
}
