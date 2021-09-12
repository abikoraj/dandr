<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FiscalYear;
use App\Models\PosBill;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosBillingController extends Controller
{
    public function index(Request $request){
        if($request->getMethod()=="POST"){
            $bills_query = PosBill::latest();
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $fy=FiscalYear::find($request->fy);
            $range = [];
            $data = [];
            $date = 1;
            $title = "";
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
                $title .= "<span class='mx-2'>Session:" . $session . "</span>";
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $bills_query = $bills_query->where('date', '=', $date);
                $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
                $title .= "<span class='mx-2'>Week:" . $week . "</span>";
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>from:" . $request->date1 . "</span>";
                $title .= "<span class='mx-2'>To:" . $request->date2 . "</span>";
            }elseif ($type == 6){
                $range[1] = $fy->startdate;
                $range[2] = $fy->enddate;
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>from:" . _nepalidate($range[1]) . "</span>";
                $title .= "<span class='mx-2'>To:" . _nepalidate($range[2]). "</span>";
            } 
            if ($request->customer_id != -1) {
                $bills_query = $bills_query->where('customer_id', $request->customer_id);
            }
            if($request->filled('bill_no')){
                $bills_query=$bills_query->where('bill_no',$request->bill_no);
            }
            $print=$request->print??0;
            $return=$request->return??0;
            $cancel=$request->cancel??0;
            $bills = $bills_query->where('is_canceled', 0)->select('date','customer_name','id','bill_no','grandtotal')->get();
            // dd($bills);
            return view('admin.pos.list',compact('bills','print','return','cancel'));
        }else{
            return view('admin.pos.index');
        }
    }

    public function detail(Request $request){
        $bill=PosBill::find($request->id);
        $bill->billitems;
        return view('admin.pos.detail',compact('bill'));
    }

    //XXX Reprint 
    public function print(Request $request){
        if($request->getMethod()=="POST"){

        }else{
            return view('admin.pos.print.index');

        }
    }

    public function printInfo(Request $request){
        $b=PosBill::find($request->id);
        $b->billitems;
        $b->payment;
        $b->user=Auth::user();
        
        return response()->json($b);
    }

     //XXX Sales Return 
     public function salesReturn(Request $request){
        if($request->getMethod()=="POST"){
            $b=PosBill::find($request->id);
            $b->billitems;
            // $b->payment;
            // $b->user=Auth::user();
            return view('admin.pos.return.init',['bill'=>$b]);
        }else{
            return view('admin.pos.return.index');

        }
    }

}
