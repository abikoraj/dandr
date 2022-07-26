<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Center;
use App\Models\Ledger;
use App\NepaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(){

        $n=new NepaliDateHelper();
        $range=$n->currentSessionRange(1);
        $month=$n->currentMonth();
        $today=nepaliToday();
        // $today=20790301;
        $milkData=[];
        foreach (Center::all(['id','name']) as $key => $center) {
            array_push($milkData,(object)[
                'center'=>$center->name,
                'amounts'=> DB::table('milkdatas')->where('center_id',$center->id)->where('date',$today)->select(DB::raw('IFNULL(sum(e_amount),0) as e_amount ,IFNULL(sum(m_amount),0)  as m_amount'))->first()??((object)['m_amount'=>0,'e_amount'=>0])
            ]);
        }

        $salesData=[];
        $salesData['farmer']= DB::selectOne("select sum(total) as total,sum(paid) as paid,sum(due) as due from sellitems where user_id in (select u.id from users u join farmers d on u.id=d.user_id) and date=${today}");
        $salesData['distributers']= DB::selectOne("select sum(total) as total,sum(paid) as paid,sum(due) as due from sellitems where user_id in (select u.id from users u join distributers d on u.id=d.user_id) and date=${today}");
        $counter1=DB::selectOne("select sum(grandtotal) as total,sum(paid) as paid,sum(due) as due from bills where date={$today}");
        $counter2=DB::selectOne("select sum(grandtotal) as total,sum(paid) as paid,sum(due) as due from pos_bills where date={$today}");

        $salesData['counter']=(object)[
            'total'=>$counter1->total+$counter2->total,
            'paid'=>$counter1->paid+$counter2->paid,
            'due'=>$counter1->due+$counter2->due,
        ];


        // dd($salesData,$milkData);

        // $snffatData=collect( DB::select("select avg(snf) as snf,avg(fat) as fat,date from  snffats group by date"));
        // $disSell=collect( DB::select("select sum(total) as total,sum(paid) as paid,sum(due) as due, date from sellitems where user_id in (select u.id from users u join distributers d on u.id=d.user_id) group by date"));
        // $farmerSell=collect( DB::select("select sum(total) as total,sum(paid) as paid,sum(due) as due, date from sellitems where user_id in (select u.id from users u join farmers d on u.id=d.user_id) group by date"));
        // dd($milkData);

        return view('admin.index',compact('milkData','salesData','range','month','today'));
    }
}
