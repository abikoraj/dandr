<?php
namespace App;

use App\Models\Advance;
use App\Models\Center;
use Illuminate\Support\Facades\DB;

class ReportManager{
    public static function farmer($request)
    {
        $t1=time();
        $farmerRange=($request->s_number != null && $request->e_number != null)?" and iu.no>= {$request->s_number} and iu.no<= {$request->e_number}   ":"";
        $range = NepaliDate::getDate($request->year,$request->month,$request->session);
        $center=Center::find($request->center_id);
        $year=$request->year;
        $month=$request->month;
        $session=$request->session;
        $usetc=(env('usetc',0)==1)&& ($center->tc>0);
        $usecc=(env('usecc',0)==1)&& ($center->cc>0);

        $query ="select  u.id,u.no,u.name,u.usecc,u.rate,u.usetc,u.userate,
        (select sum(m_amount) + sum(e_amount) from milkdatas where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as milk,
        (select avg(snf) from snffats where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as snf,
        (select avg(fat) from snffats where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as fat,
        (select sum(amount) from advances where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as advance,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=121) as paidamount,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=106 or identifire=107)) as fpaid,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=103) as purchase,
        (select sum(amount) from ledgers where user_id= u.id and date<{$range[1]} and type=1) as prevcr,
        (select sum(amount) from ledgers where user_id= u.id and date<{$range[1]} and type=2) as prevcdr,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=101 or identifire=102) and type=1) as openingcr,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=101 or identifire=102) and type=2) as openingdr,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=120 and type=1) as closingcr,
        (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=120  and type=2) as closingdr
        from (select iu.name,iu.id,iu.no,f.usecc,f.rate,f.usetc,f.userate from users iu join farmers f on iu.id=f.user_id where f.center_id={$center->id}  {$farmerRange}) u";
        $reports=DB::table('farmer_reports')->where(['year'=>$year,'month'=>$month,'session'=>$session])->get();

        $farmers=DB::select($query);
        $t2=time();
        dd($farmers,$t2-$t1);

    }
}
