<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Farmer;
use App\Models\FarmerReport;
use App\Models\SessionWatch;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerReportController extends Controller
{
    public function farmerWithMilk(Request $request)
    {
        if($request->getMethod()=="POST"){
            $t1 = time();
            $farmerRange = ($request->s_number != null && $request->e_number != null) ? " and iu.no>= {$request->s_number} and iu.no<= {$request->e_number}   " : "";
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $center = Center::find($request->center_id);
            $year = $request->year;
            $month = $request->month;
            $session = $request->session??1;
            $usetc = (env('usetc', 0) == 1) && ($center->show_ts);
            $usecc = (env('usecc', 0) == 1) && ($center->show_cc);

            $useprotsahan = (env('useprotsahan', 0) == 1) && ($center->use_protsahan);
            $usetransport = (env('usetransportamount', 0) == 1) && ($center->use_transport);

            $newsession = SessionWatch::where(['year' => $year, 'month' => $month, 'session' => $session, 'center_id' => $center->id])->count() == 0;

            $milkdatas=Collect(DB::select("select sum(m_amount) as morning,sum(e_amount) as evening,user_id,date from milkdatas 
                where date>={$range[1]} and date<={$range[2]} and 
                user_id in (select iu.id from users iu join farmers f on iu.id=f.user_id where f.center_id={$center->id}  {$farmerRange}  order by iu.no asc ) group by user_id,date"));
        

            $query = "select  u.*,
            (select sum(m_amount) + sum(e_amount) from milkdatas where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as milk,
            (select avg(snf) from snffats where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as snf,
            (select avg(fat) from snffats where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as fat,
            ifnull((select sum(amount) from advances where user_id= u.id and date>={$range[1]} and date<={$range[2]}),0) as advance,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=121),0) as paidamount,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=408),0) as jinsipaid,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=409),0) as jinsipurchase,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=106 or identifire=107)),0) as fpaid,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=103),0) as purchase,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date<{$range[1]} and type=1),0) as prevcr,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date<{$range[1]} and type=2),0) as prevdr,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=101 or identifire=102) and type=1),0) as openingcr,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=101 or identifire=102) and type=2),0) as openingdr,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=120 and type=1),0) as closingcr,
            ifnull((select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=120  and type=2),0) as closingdr
            from (select iu.name,iu.id,iu.no,f.usecc,f.rate,f.usetc,f.userate,f.ts_amount,f.use_ts_amount,f.protsahan,f.use_protsahan,f.transport,f.use_transport,f.use_custom_rate,f.snf_rate,f.fat_rate  from users iu join farmers f on iu.id=f.user_id where f.center_id={$center->id}  {$farmerRange}) u order by u.no asc";
            $reports = DB::table('farmer_reports')->where(['year' => $year, 'month' => $month, 'session' => $session])->get();
            // $milkdatas=DB::table('milkdatas')->select(DB::raw('sum(m_amount) as morning,sum(e_amount) as evening,user_id,date '))->groupBy('user_id','date')->whereIn('uesr_id',)->get();

            $farmers = DB::select($query);

            $firstPage = env('firstpage', 31);
            $secondPage = env('secondpage', 34);
            $firstLoaded = false;
            $datas = [];
            $minList = [];
            $index = 1;

            foreach ($farmers as $key => $farmer) {
                $farmer->old = $reports->where('user_id', $farmer->id)->count() > 0;
                // $farmer->milkdata=$milkdatas->where('user_id',$farmer->id)->groupBy('date');
                $farmer->fat = truncate_decimals($farmer->fat);
                $farmer->snf = truncate_decimals($farmer->snf);
                if ($farmer->use_custom_rate) {
                    $fatAmount = ($farmer->fat * $farmer->fat_rate);
                    $snfAmount = ($farmer->snf * $farmer->snf_rate);
                } else {

                    $fatAmount = ($farmer->fat * $center->fat_rate);
                    $snfAmount = ($farmer->snf * $center->snf_rate);
                }

                $report = $reports->where('user_id', $farmer->id)->first();
                $hasRate = false;
                $farmer->tc = 0;
                $farmer->cc = 0;
                $farmer->protsahan_amount = 0;
                $farmer->transport_amount=0;
                if ($farmer->old) {
                    if ($report->has_passbook == 1) {
                        $farmer->rate = $report->rate;
                        $farmer->cc = $report->cc;
                        $farmer->tc = $report->tc;
                        $farmer->protsahan_amount=$report->protsahan_amount;
                        $farmer->transport_amount=$report->transport_amount;
                        $hasRate = true;
                    }
                }
                if (!$hasRate) {
                    
                    if ($farmer->userate == 1) {
                        
                        $farmer->rate = $farmer->rate;
                    } else {
                        
                        $farmer->rate = truncate_decimals($fatAmount + $snfAmount);
                    }

                    $farmer->total = truncate_decimals(($farmer->rate * $farmer->milk), 2);

                    if ($farmer->usetc == 1 && $farmer->total > 0) {
                        $farmer->tc = truncate_decimals((($center->tc * ($farmer->snf + $farmer->fat) / 100) * $farmer->milk), 2);
                    }
                    if ($farmer->use_ts_amount == 1 && $farmer->total > 0) {
                        $farmer->tc = truncate_decimals((($farmer->ts_amount) * $farmer->milk), 2);
                    }
                    if ($farmer->usecc == 1 && $farmer->total > 0) {
                        $farmer->cc = truncate_decimals($center->cc * $farmer->milk, 2);
                    }
                    if ($farmer->use_protsahan == 1 && $farmer->total > 0) {
                        $farmer->protsahan_amount = truncate_decimals($farmer->protsahan * $farmer->milk, 2);
                    }
                    if ($farmer->use_transport == 1 && $farmer->total > 0) {
                        $farmer->transport_amount = truncate_decimals($farmer->transport * $farmer->milk, 2);
                    }
                }else{
                    $farmer->total = truncate_decimals(($farmer->rate * $farmer->milk), 2);

                }



                $farmer->bonus = 0;
                if (env('hasextra', 0) == 1) {
                    $farmer->bonus = (int)($farmer->grandtotal * $center->bonus / 100);
                }

                $farmer->grandtotal = (int)($farmer->total + $farmer->tc + $farmer->cc+$farmer->protsahan_amount+$farmer->transport_amount );
                $prev = $farmer->prevdr - $farmer->prevcr;
                $opening = $farmer->openingdr - $farmer->openingcr;
                $farmer->prevTotal = $prev + $opening;



                if ($farmer->prevTotal > 0) {
                    $farmer->prevdue = $farmer->prevTotal;
                    $farmer->prevbalance = 0;
                } else if($farmer->prevTotal<0) {
                    $farmer->prevdue = 0;
                    $farmer->prevbalance = (-1) * $farmer->prevTotal;
                }else{
                    $farmer->prevdue = 0;
                    $farmer->prevbalance = 0;
                    
                }

                $farmer->balance = 0;
                $farmer->nettotal = 0;

                $farmer->paidamount += $farmer->jinsipurchase;
                $farmer->fpaid += $farmer->jinsipaid;

                $farmer->sessiondata=$farmer->fpaid
                + $farmer->prevbalance
                - $farmer->prevdue
                - $farmer->advance
                - $farmer->purchase
                - $farmer->bonus;

                $farmer->advancetotal=0;
                $farmer->balancetotal=0;
                if($farmer->sessiondata>0){
                    $farmer->balancetotal=$farmer->sessiondata;

                }else{
                    $farmer->advancetotal=(-1)*$farmer->sessiondata;
                }


                $balance = $farmer->grandtotal
                    + $farmer->fpaid
                    + $farmer->prevbalance
                    - $farmer->prevdue
                    - $farmer->advance
                    - $farmer->purchase
                    - $farmer->paidamount
                    - $farmer->bonus;

                if ($balance < 0) {
                    $farmer->balance = (-1) * $balance;
                }
                if ($balance > 0) {
                    $farmer->nettotal = $balance;
                }

                array_push($minList, $farmer);
                $index += 1;
                if ($firstLoaded) {
                    if ($index == $secondPage) {
                        array_push($datas, ['farmers' => $minList, 'full' => true, 'count' => count($minList)]);
                        $index = 1;
                        $minList = [];
                    }
                } else {
                    if ($index == $firstPage) {
                        array_push($datas, ['farmers' => $minList, 'full' => true, 'count' => count($minList)]);
                        $firstLoaded = true;
                        $index = 1;
                        $minList = [];
                    }
                }
            }

            if (count($minList) > 0) {
                array_push($datas, ['farmers' => $minList, 'full' => false, 'count' => count($minList)]);
            }


            $t2 = time();
            $sessionDate = NepaliDate::getDateSessionLast($year, $month, $session);
            // dd($sessionDate);
            // dd($t2-$t1,$datas);
            // dd(compact('newsession', 'usetc', 'usecc', 'datas', 'year', 'month', 'session', 'center', 'sessionDate','useprotsahan','usetransport'));
            if($request->filled('show_detail')){
                return view('admin.report.farmer.withmilk.data', compact('newsession', 'usetc', 'usecc', 'datas', 'year', 'month', 'session', 'center', 'sessionDate','useprotsahan','usetransport','range','milkdatas'));
            }else{
                
                return view('admin.report.farmer.withmilk.datawithOutDetail', compact('newsession', 'usetc', 'usecc', 'datas', 'year', 'month', 'session', 'center', 'sessionDate','useprotsahan','usetransport','range','milkdatas'));
            }
        
            // return view('admin.report.farmer.data', );
            
        }else{
            return view('admin.report.farmer.withmilk.index');
        }
    }
}
