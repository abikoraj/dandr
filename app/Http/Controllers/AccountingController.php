<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use App\Models\Ledger;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {

        return view('admin.accounting.index');
    }

    public function final(Request $request)
    {
        if ($request->getMethod() == "POST") {

            $showDetail=$request->filled('detail');
            $range = [];
            $type = $request->type;
            $fy = FiscalYear::where('id', $request->fy)->first();
            if ($fy == null) {
                throw new \Exception('Fiscal Year Not Found');
            }
            $startYear = (int) ($fy->startdate / 10000);
            $endYear = (int) ($fy->enddate / 10000);

            if ($type == 1) {
                $range[1] = $fy->startdate;
                $range[2] = $fy->enddate;
            } else if ($type == 2) {
                $q = $request->quater;
                $y=$q<4?$startYear:$endYear;
                $range = [
                    NepaliDate::getDateMonth($y, $q < 4 ? ($q * 3) + 1 : 1)[1],
                    NepaliDate::getDateMonth($y,$q < 4 ? (($q + 1) * 3) : 3)[2]
                ];
            } else if ($type == 3) {
                if ($request->month < 4) {
                    $range = NepaliDate::getDateMonth($endYear, $request->month);
                } else {
                    $range = NepaliDate::getDateMonth($startYear, $request->month);
                }
            }

            $opening = DB::selectOne("select id,opening from stocks where date>={$range[1]} and date <={$range[2]} and opening is not null order by date,id asc limit 1")->opening;
            $closing = DB::selectOne("select id,closing from stocks where date>={$range[1]} and date <={$range[2]} and closing is not null order by date,id desc limit 1")->closing;
            // dd($opening,$closing);
            $queries=[
                'milk'=>" select sum(amount) from ledgers where identifire=108 ",
                'supplier'=>"select sum(total) from supplierbills where canceled=0 ",
                'purchaseExpense'=>"select sum(be.amount) from bill_expenses be join supplierbills b on be.supplierbill_id=b.id where canceled=0 ",
                'counter1'=>"select sum(grandtotal) from pos_bills where is_canceled=0 ",
                'counter2'=>"select sum(grandtotal) from bills where id>0 ",
                'farmer'=>"select  sum(qty*rate) from sellitems where id>0 ",
                'distributer'=>"select  total from distributorsells where id>0 ",
            ];
            $temp=[];
            foreach ($queries as $key => $query) {
                array_push($temp,"( ".$query." and (date>={$range[1]} and date<={$range[2]} )) as {$key}");
            }

            $query ="select ".implode(",",$temp);


            $trading=DB::selectOne(
                $query
            );

            $trading->counter=$trading->counter1 + $trading->counter2;
            $trading->sales=$trading->counter+$trading->farmer+$trading->distributer;
            $trading->cr=$trading->sales+$closing;
            $trading->purchase=$trading->milk+$trading->supplier;
            $trading->dr = $opening + $trading->purchase +$trading->purchaseExpense;
            $trading->status=($trading->cr==$trading->dr)?'none':($trading->cr>$trading->dr?'profit':'loss');
            if($trading->status=='profit'){
                $trading->profit=$trading->cr-$trading->dr;
                $trading->loss=null;
                $trading->total=$trading->cr;
            }else if($trading->status=='loss'){
                $trading->loss=$trading->dr-$trading->cr;
                $trading->profit=null;
                $trading->total=$trading->dr;

            }else{
                $trading->loss=null;
                $trading->profit=null;
                $trading->total=$trading->cr;
            }



            $plac=(object)[];
            $plac->dr=0;
            $plac->cr=0;
            $plac->salary=DB::selectOne('select sum(amount) as amount from ledgers where identifire=129 and (date>=? and date<=?)',[$range[1],$range[2]])->amount;
            $plac->expenses=DB::select("select e.amount,etype.name,e.expcategory_id from
            (select expcategory_id,sum(amount) as amount from expenses  where date>={$range[1]} and date <={$range[2]} group by expcategory_id) e
            join expcategories etype on etype.id=e.expcategory_id");
            $plac->incomes=DB::select("select ei.amount,eic.name from
            (select sum(amount) as amount,extra_income_category_id from extra_incomes  where date>={$range[1]} and date <={$range[2]} group by extra_income_category_id)  ei
            join extra_income_categories eic on eic.id=ei.extra_income_category_id");
            if($trading->status=='loss'){
                $plac->dr+=$trading->loss;
            }else if($trading->status=="profit"){
                $plac->cr+=$trading->profit;
            }

            $plac->dr+=$plac->salary;

            foreach ($plac->expenses as $key => $expense) {
                $plac->dr+=$expense->amount;
            }
            foreach ($plac->incomes as $key => $income) {
                $plac->cr+=$income->amount;
            }

            $plac->status=($plac->cr==$plac->dr)?'none':($plac->cr>$plac->dr?'profit':'loss');
            if($plac->status=='profit'){
                $plac->profit=$plac->cr-$plac->dr;
                $plac->loss=null;
                $plac->total=$plac->cr;
            }else if($plac->status=='loss'){
                $plac->loss=$plac->dr-$plac->cr;
                $plac->profit=null;
                $plac->total=$plac->dr;

            }else{
                $plac->loss=null;
                $plac->profit=null;
                $plac->total=$plac->cr;
            }


            // dd($trading,$plac);

            return view('admin.accounting.final.index',compact('trading','opening','closing','showDetail','range','plac'));
        } else {
            $fys = DB::table('fiscal_years')->get(['id', 'name', 'startdate', 'enddate']);
            return view('admin.accounting.final.result', compact('fys'));
        }
    }
}
