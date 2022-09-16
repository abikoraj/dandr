<?php

namespace App\Http\Controllers;

use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseReportController extends Controller
{
    public function fy(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $monthArray = [];
            $fy = FiscalYear::where('id', $request->fy)->first();
            $startYear = (int)($fy->startdate / 10000);
            $month = 4;
            for ($i = 0; $i < 12; $i++) {
                $monthSTR = $month < 10 ? ('0' . $month) : $month;
                array_push($monthArray, [$startYear * 100 + $month, "{$startYear}-{$monthSTR}"]);
                $month += 1;
                if ($month > 12) {
                    $month = 1;
                    $startYear += 1;
                }
            }
            $expenses = Collect(DB::select("select sum(e.amount) as amount,c.id,e.month from 
            (select amount,cast((date/100) as int) as month,expcategory_id from expenses where date>={$fy->startdate} and date<={$fy->enddate}) e 
            join expcategories c on c.id=e.expcategory_id 
            group by c.id,e.month "));
            $cats = DB::select('select id,name from expcategories');

            $salaries = Collect(DB::select("select sum(c.amount) as amount,c.month from  (select amount, (year*100+month) as month from salary_payments where date>={$fy->startdate} and date<={$fy->enddate}) c group by c.month"));
            $purchaseExps = Collect(
                DB::select("select sum(c.amount) as amount,c.month from
                (select b.amount, cast((s.date/100) as int) as month from bill_expenses b join supplierbills s on b.supplierbill_id=s.id where s.date>={$fy->startdate} and s.date<={$fy->enddate}) c
                group by c.month")
            );

            return view('admin.report.expense.fy.data', compact('expenses', 'cats', 'monthArray', 'salaries', 'purchaseExps'));
        } else {
            return view('admin.report.expense.fy.index');
        }
    }
}
