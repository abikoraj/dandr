<?php

namespace App\Http\Controllers;

use App\LedgerManage;
use App\Models\Advance;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Center;
use App\Models\CreditNote;
use App\Models\DistributorPayment;
use App\Models\Distributer;
use App\Models\Distributorsell;
use App\Models\Employee;
use App\Models\Farmer;
use App\Models\FarmerReport;
use App\Models\Ledger;
use App\Models\Milkdata;
use App\Models\Sellitem;
use App\Models\Product;
use App\Models\Snffat;
use App\Models\SessionWatch;
use App\Models\FarmerSession;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeReport;
use App\Models\Expense;
use App\Models\FiscalYear;
use App\Models\PosBill;
use App\Models\PosBillItem;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as DB;

use function PHPSTORM_META\type;

class ReportManager
{
    //Pos
    public static function makeGroup($type, $request)
    {
        $dataGroup = [];
        $nepaliDate = new NepaliDate(20000101);

        $dataGroup['dates'] = [];
        $dataGroup['sales'] = [];
        $dataGroup['return'] = [];
        $dataGroup['type'] = $type;
        $dataGroup['sales_label']="Sales";
        $dataGroup['return_label']="Sales Return";
        $initialDate = 0;
        $endDate = 0;
        if ($type == 0 || $type == 2 || $type == 3) {
            $to = 15;

            switch ($type) {
                case 0:
                    $initialDate = $request->year * 10000 + $request->month * 100 + ($request->session == 1 ? 1 : 16);
                    $endDate = $request->year * 10000 + $request->month * 100 + ($request->session == 1 ? 15 : $nepaliDate->getBS()[$request->year - 2000][$request->month]);
                    break;
                case 2:
                    $weekRange = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                    $monthend = $request->year * 10000 + $request->month * 100 +  $nepaliDate->getBS()[$request->year - 2000][$request->month];
                    $initialDate = $weekRange[1];
                    $endDate = $weekRange[2];
                    if ($endDate > $monthend) {
                        $endDate = $monthend;
                    }
                    break;
                default:
                    $initialDate = $request->year * 10000 + $request->month * 100 + 1;
                    $endDate = $request->year * 10000 + $request->month * 100 +  $nepaliDate->getBS()[$request->year - 2000][$request->month];
                    break;
            }
            $pos_bills = PosBill::select(DB::raw('sum(grandtotal) as total ,date'))
                ->where('date', '>=', $initialDate)->where('date', '<=', $endDate)->groupBy('date')->orderBy('date')->get();
            $sales_returns = CreditNote::select(DB::raw('sum(total) as total ,date'))
                ->where('date', '>=', $initialDate)->where('date', '<=', $endDate)->groupBy('date')->orderBy('date')->get();
            for ($i = $initialDate; $i <= $endDate; $i++) {
                $data = $pos_bills->firstWhere('date', $i);
                $data1 = $sales_returns->firstWhere('date', $i);
                array_push($dataGroup['dates'], _nepalidate($i));
                array_push($dataGroup['sales'], $data != null ? $data->total : 0);
                array_push($dataGroup['return'], $data1 != null ? $data1->total : 0);
            }

        } else if($type==4 || $type==6) {
            $initialDate = $request->year * 10000 + 100 + 1;
            $endDate = $request->year * 10000 + 1200 +  32;


            $month_arr=[1,2,3,4,5,6,7,8,9,10,11,12];

            if($type==6){
                $month_arr=[4,5,6,7,8,9,10,11,12,1,2,3];
                $fiscalYear=FiscalYear::find($request->fiscalYear);
                $initialDate = $fiscalYear->startdate;
                $endDate = $fiscalYear->enddate;
            }

            $pos_bills=PosBill::select(DB::raw('sum(grandtotal) as total,cast(substring(cast(date as varchar(8)),5,2) as int) as month '))
            ->where('date', '>=', $initialDate)->where('date', '<=', $endDate)
            ->groupBy('month')->get();
            $sales_returns=CreditNote::select(DB::raw('sum(total) as total,cast(substring(cast(date as varchar(8)),5,2) as int) as month '))
            ->where('date', '>=', $initialDate)->where('date', '<=', $endDate)
            ->groupBy('month')->get();

            foreach ($month_arr as $key => $month) {
                $data = $pos_bills->firstWhere('month', $month);
                $data1 = $sales_returns->firstWhere('month', $month);
                array_push($dataGroup['dates'],$nepaliDate->get_nepali_month( $month));
                array_push($dataGroup['sales'], $data != null ? $data->total : 0);
                array_push($dataGroup['return'], $data1 != null ? $data1->total : 0);
            }
            // dd($dataGroup);
        }else if($type==5){
            $initialDate = str_replace('-', '', $request->date1);
            $endDate = str_replace('-', '', $request->date2);
            $dataGroup['sales'] =PosBill::where('date', '>=', $initialDate)
            ->where('date', '<=', $endDate)
            ->sum('grandtotal');
            $dataGroup['return']=CreditNote::where('date', '>=', $initialDate)
            ->where('date', '<=', $endDate)
            ->sum('total');
        }else{
            $date = str_replace('-', '', $request->date1);
            $dataGroup['sales'] = PosBill::where('date',$date)->sum('grandtotal');
            $dataGroup['return'] = CreditNote::where('date',$date)->sum('total');
        }
        return $dataGroup;

    }
}
