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
        $milkData=[];
        foreach (Center::all(['id','name']) as $key => $center) {
            array_push()
        }
        $milkData=DB::select("select m.* ,c.name from centers c join
         (select center_id,(sum(e_amount)+sum(m_amount)) as amount from milkdatas where date=20790301 group by center_id ) m
         on c.id=m.center_id
        ");
        dd($milkData);
        // $milkData=collect( DB::select("select sum(m_amount) as m_amount,sum(e_amount) as e_amount,date from  milkdatas group by date"));
        // $snffatData=collect( DB::select("select avg(snf) as snf,avg(fat) as fat,date from  snffats group by date"));
        // $disSell=collect( DB::select("select sum(total) as total,sum(paid) as paid,sum(due) as due, date from sellitems where user_id in (select u.id from users u join distributers d on u.id=d.user_id) group by date"));
        // $farmerSell=collect( DB::select("select sum(total) as total,sum(paid) as paid,sum(due) as due, date from sellitems where user_id in (select u.id from users u join farmers d on u.id=d.user_id) group by date"));
        // dd($milkData);

        return view('admin.index',compact('milkData','snffatData','disSell','farmerSell','range','month'));
    }
}
