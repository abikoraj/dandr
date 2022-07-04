<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FixedAsset;
use App\Models\FixedAssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixedAssetController extends Controller
{
    public function index(Account $account)
    {
        $fixedAssets=DB::select('select fa.*,cat.name as category from  fixed_assets fa join fixed_asset_categories cat on fa.fixed_asset_category_id=cat.id where fa.account_id=?',[$account->id]);
        // dd($fixedAssets);
        $cats=DB::table('fixed_asset_categories')->get();
        return view('admin.accounting.accounts.fixedassets.index',compact('fixedAssets','account','cats'));
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
        $fixedAsset->fixed_asset_category_id=$request->fixed_asset_category_id;
        $fixedAsset->salvage_amount=$request->salvage_amount??0;
        $fixedAsset->save();
        $fixedAsset->category=assetCategory($fixedAsset->fixed_asset_category_id);
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
            $fixedAsset->fixed_asset_category_id=$request->fixed_asset_category_id;
            $fixedAsset->salvage_amount=$request->salvage_amount??0;

            $fixedAsset->save();
            $fixedAsset->category=assetCategory($fixedAsset->fixed_asset_category_id);
            return view('admin.accounting.accounts.fixedassets.single',compact('fixedAsset'));

        }else{
            $fixedAsset=DB::table('fixed_assets')->where('id',$id)->first();
            $cats=DB::table('fixed_asset_categories')->get();

            return view('admin.accounting.accounts.fixedassets.edit',compact('fixedAsset','id','cats'));
        }
    }

    public function categoryIndex()
    {
        $cats=DB::table('fixed_asset_categories')->get();
        return view('admin.accounting.accounts.fixedassets.category.index',compact('cats'));
    }
    public function categoryAdd(Request $request)
    {
        $cat=new FixedAssetCategory();
        $cat->name=$request->name;
        $cat->depreciation=$request->depreciation;
        $cat->save();
        return redirect()->back();
    }
    public function categoryUpdate(Request $request)
    {
        $cat=FixedAssetCategory::where('id',$request->id)->first();
        $cat->name=$request->name;
        $cat->depreciation=$request->depreciation;
        $cat->save();
        return redirect()->back();
    }
    public function categoryDel(Request $request)
    {
        $cat=FixedAssetCategory::where('id',$request->id)->first();
        $cat->delete();
        return redirect()->back();
    }
}
