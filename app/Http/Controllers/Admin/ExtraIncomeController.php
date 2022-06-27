<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraIncome;
use App\Models\ExtraIncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExtraIncomeController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $query=DB::table('extra_incomes as ei')
            ->join('extra_income_categories as eic','eic.id','=','ei.extra_income_category_id');
            $query=rangeSelector($request,$query);
            $incomes=$query->select(DB::raw('ei.id,ei.amount,ei.title,ei.date,eic.name as category,ei.received_by'))->get();
            return response()->json($incomes);

        }else{
            return view('admin.accounting.extraincome.index');
        }
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="POST"){
            $income=new ExtraIncome();
            $income->extra_income_category_id=$request->extra_income_category_id;
            $income->date=str_replace('-','', $request->date);
            $income->title=$request->title;
            $income->amount=$request->amount;
            $income->received_by=$request->received_by;
            $income->payment_detail=$request->payment_detail;
            $income->save();
            return response()->json(['status'=>true]);
        }else{
            $cats=DB::select('select
            id,name
            from extra_income_categories');
            return view('admin.accounting.extraincome.add',compact('cats'));
        }
    }
    public function update(Request $request,$id)
    {
        $income=ExtraIncome::where('id',$request->id)->first();
        if($request->getMethod()=="POST"){
            $income->extra_income_category_id=$request->extra_income_category_id;
            $income->date=str_replace('-','', $request->date);
            $income->title=$request->title;
            $income->amount=$request->amount;
            $income->received_by=$request->received_by;
            $income->payment_detail=$request->payment_detail;
            $income->save();
            return response()->json(['status'=>true]);
        }else{
            $cats=DB::select('select
            id,name
            from extra_income_categories');
            return view('admin.accounting.extraincome.update',compact('cats','income'));
        }
    }

    public function category()
    {
        $cats=DB::select('select
         id,name,
         ifnull((select count(*) from extra_incomes
         where extra_incomes.extra_income_category_id=extra_incomes.id),0) as c
         from extra_income_categories');
        return view('admin.accounting.extraincome.category',compact('cats'));
    }
    public function categoryAdd(Request $request)
    {
        $cat=new ExtraIncomeCategory();
        $cat->name=$request->name;
        $cat->save();
        return redirect()->back();
    }
    public function categoryUpdate(Request $request)
    {
        $cat=ExtraIncomeCategory::where('id',$request->id)->first();
        $cat->name=$request->name;
        $cat->save();
        return redirect()->back();
    }
    public function categoryDel(Request $request)
    {
        $cat=ExtraIncomeCategory::where('id',$request->id)->first();
        $cat->delete();
        return redirect()->back();
    }


}
