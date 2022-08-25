<?php

namespace App\Http\Controllers;

use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantReportController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $type=$request->type;
            $bill=DB::table('bills');
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $bill = $bill->where('bills.date', '>=', $range[1])->where('bills.date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $bill = $bill->where('bills.date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $bill = $bill->where('bills.date', '>=', $range[1])->where('bills.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $bill = $bill->where('bills.date', '>=', $range[1])->where('bills.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $bill = $bill->where('bills.date', '>=', $range[1])->where('bills.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $bill = $bill->where('bills.date', '>=', $range[1])->where('bills.date', '<=', $range[2]);
            }

            if($request->center_id!=-1){
                $bill=$bill->where('center_id',$request->center_id);

            }
            $bills=$bill
            ->select(                DB::raw("id,(select group_concat(concat(name,' x ',qty) SEPARATOR ', ')  from bill_items where bill_items.bill_id=bills.id) as billitems,name,net_total,center_id,paid,due,date,is_canceled,dis"))
            ->get();
            return view('admin.report.restaurant.data',compact('bills'));
        }else{
            return view('admin.report.restaurant.index');
        }
    }
}
