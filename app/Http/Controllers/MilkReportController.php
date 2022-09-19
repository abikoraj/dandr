<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\FarmerReport;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkReportController extends Controller
{
    public function fy(Request $request)
    {
        if($request->getMethod()=="POST"){
            $fy = FiscalYear::where('id', $request->fy)->first();
            $month = getFiscalYearMonth($fy);
            $monthArray = getFiscalYearMonth($fy);
            $data = Collect(DB::select("select sum(grandtotal) as amount, year, month, sum(milk) as milk,center_id from farmer_reports group by center_id, year, month"));
            // dd($monthArray);
            // where date>={$fy->startdate} and date<={$fy->enddate}
             $datas = (object)$data;
             $centers=DB::table('centers')->get(['name','id']);
            //  $centerData=[];
            //  foreach ($datas as $key => $value) {
            //    $center = Center::where('id',$value->center_id)->first();
            //    array_push($centerData,$center);
            //  }
            //  dd($centerData);
            return view('admin.report.milk.fy.data',compact('datas','monthArray','centers'));
        }else{
            return view('admin.report.milk.fy.index');
        }
    }
}
