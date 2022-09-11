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

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function farmerOLD(Request $request)
    {
        if ($request->getMethod() == "POST") {
            // dd($request->all());
            if ($request->s_number != null && $request->e_number != null) {
                $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('users.no', '>=', $request->s_number)->where('users.no', '<=', $request->e_number)->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();
            } else {
                $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();
            }

            $center = Center::find($request->center_id);
            $year = $request->year;
            $month = $request->month;
            $session = $request->session;
            $usetc = (env('usetc', 0) == 1) && ($center->tc > 0);
            $usecc = (env('usecc', 0) == 1) && ($center->cc > 0);

            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $newsession = SessionWatch::where(['year' => $year, 'month' => $month, 'session' => $session, 'center_id' => $center->id])->count() == 0;

            // if(SessionWatch::where(['year'=>$year,'month'=>$month,'session'=>$session,'center_id'=>$center->id])->count()>0){
            //     $data=FarmerReport::where(['year'=>$year,'month'=>$month,'session'=>$session,'center_id'=>$center->id])->get();
            //     return view('admin.report.farmer.data1',compact('usecc','usetc','data','year','month','session','center'));

            // }else{

            $data = [];
            foreach ($farmers as $farmer) {
                if (FarmerReport::where(['year' => $year, 'month' => $month, 'session' => $session, 'user_id' => $farmer->id])->count() > 0) {
                    $_data = FarmerReport::where(['year' => $year, 'month' => $month, 'session' => $session, 'user_id' => $farmer->id])->first();
                    $farmer->old = true;
                } else {
                    $_data = LedgerManage::farmerReport($farmer->id, $range);
                }
                $farmer->milk = $_data->milk;
                $farmer->fpaid = $_data->fpaid;
                $farmer->fat = $_data->fat;
                $farmer->snf = $_data->snf;
                $farmer->rate = $_data->rate;
                $farmer->total = $_data->totalamount;
                $farmer->tc = $_data->tc;
                $farmer->cc = $_data->cc;
                $farmer->grandtotal = $_data->grandtotal;
                $farmer->bonus = $_data->bonus;
                $farmer->prevdue = $_data->prevdue;
                $farmer->due = $_data->due;
                $farmer->advance = $_data->advance;
                $farmer->prevbalance = $_data->prevbalance;
                $farmer->paidamount = $_data->paidamount;
                $farmer->nettotal = $_data->nettotal;
                $farmer->balance = $_data->balance;
                $farmer->advance = $_data->advance;
                array_push($data, $farmer);
            }
            return view('admin.report.farmer.data', compact('newsession', 'usetc', 'usecc', 'data', 'year', 'month', 'session', 'center'));
            // }
        } else {

            return view('admin.report.farmer.index');
        }
    }
    public function farmer(Request $request)
    {
        if ($request->getMethod() == "POST") {

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


            $query = "select  u.id,u.no,u.name,u.usecc,u.rate,u.usetc,u.userate,u.ts_amount,u.use_ts_amount,u.protsahan,u.use_protsahan,u.transport,u.use_transport,
            (select sum(m_amount) + sum(e_amount) from milkdatas where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as milk,
            (select avg(snf) from snffats where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as snf,
            (select avg(fat) from snffats where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as fat,
            (select sum(amount) from advances where user_id= u.id and date>={$range[1]} and date<={$range[2]}) as advance,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=121) as paidamount,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=106 or identifire=107)) as fpaid,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=103) as purchase,
            (select sum(amount) from ledgers where user_id= u.id and date<{$range[1]} and type=1) as prevcr,
            (select sum(amount) from ledgers where user_id= u.id and date<{$range[1]} and type=2) as prevdr,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=101 or identifire=102) and type=1) as openingcr,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and (identifire=101 or identifire=102) and type=2) as openingdr,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=120 and type=1) as closingcr,
            (select sum(amount) from ledgers where user_id= u.id and date>={$range[1]} and date<={$range[2]} and identifire=120  and type=2) as closingdr
            from (select iu.name,iu.id,iu.no,f.usecc,f.rate,f.usetc,f.userate,f.ts_amount,f.use_ts_amount,f.protsahan,f.use_protsahan,f.transport,f.use_transport  from users iu join farmers f on iu.id=f.user_id where f.center_id={$center->id}  {$farmerRange}) u order by u.no asc";
            $reports = DB::table('farmer_reports')->where(['year' => $year, 'month' => $month, 'session' => $session])->get();

            $farmers = DB::select($query);

            $firstPage = env('firstpage', 31);
            $secondPage = env('secondpage', 34);
            $firstLoaded = false;
            $datas = [];
            $minList = [];
            $index = 1;

            foreach ($farmers as $key => $farmer) {
                $farmer->old = $reports->where('user_id', $farmer->id)->count() > 0;

                $farmer->fat = truncate_decimals($farmer->fat);
                $farmer->snf = truncate_decimals($farmer->snf);
                $fatAmount = ($farmer->fat * $center->fat_rate);
                $snfAmount = ($farmer->snf * $center->snf_rate);

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
            return view('admin.report.farmer.data', compact('newsession', 'usetc', 'usecc', 'datas', 'year', 'month', 'session', 'center', 'sessionDate','useprotsahan','usetransport'));
        } else {

            return view('admin.report.farmer.index');
        }
    }

    public function farmerSingleSession(Request $request)
    {
        $lastdate = $lastdate = str_replace('-', '', $request->date);;
        $ledger = new LedgerManage($request->id);
        if (env('acc_system', "old") == "old") {
            if (env('hasextra', 0) == 1) {
                $ledger->addLedger("Bonus", 1, $request->bonus, $lastdate, '124');
            }
            if ($request->grandtotal > 0) {
                $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 2, $request->grandtotal, $lastdate, '108');
            }
        } else {
            if (env('hasextra', 0) == 1) {
                $ledger->addLedger("Bonus", 2, $request->bonus, $lastdate, '124');
            }
            if ($request->grandtotal > 0) {
                $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 1, $request->grandtotal, $lastdate, '108');
            }
        }
        $farmerreport = new FarmerReport();
        $farmerreport->user_id = $request->id;
        $farmerreport->milk = $request->milk;
        $farmerreport->snf = $request->snf ?? 0;
        $farmerreport->fat = $request->fat ?? 0;
        $farmerreport->rate = $request->rate ?? 0;
        $farmerreport->total = $request->total ?? 0;
        $farmerreport->due = $request->due ?? 0;
        $farmerreport->bonus = $request->bonus ?? 0;
        $farmerreport->prevdue = $request->prevdue ?? 0;
        $farmerreport->advance = $request->advance ?? 0;
        $farmerreport->nettotal = $request->nettotal ?? 0;
        $farmerreport->balance = $request->balance ?? 0;
        $farmerreport->paidamount = $request->paidamount ?? 0;
        $farmerreport->prevbalance = $request->prevbalance ?? 0;
        $farmerreport->tc = $request->tc ?? 0;
        $farmerreport->cc = $request->cc ?? 0;
        $farmerreport->protsahan_amount = $request->protsahan_amount ?? 0;
        $farmerreport->transport_amount = $request->transport_amount ?? 0;
        $farmerreport->grandtotal = $request->grandtotal ?? $request->total;
        $farmerreport->year = $request->year;
        $farmerreport->month = $request->month;
        $farmerreport->session = $request->session??1;
        $farmerreport->fpaid = $request->fpaid;
        $farmer = Farmer::where('user_id', $request->id)->first();
        $farmerreport->center_id = $farmer->center_id;
        $farmerreport->save();
        return redirect()->back();
    }

    public function farmerSession(Request $request)
    {
        $nextdate = NepaliDate::getNextDate($request->year, $request->month, $request->session);
        $lastdate = str_replace('-', '', $request->date);

        foreach ($request->farmers as $farmer) {
            $data = json_decode($farmer);
            // dd($data);
            $ledger = new LedgerManage($data->id);
            $grandtotal = $data->grandtotal ?? 0;
            if ($data->grandtotal > 0) {
                if (env('acc_system', "old") == "old") {
                    $ledger->addLedger("Payment for milk (" . ($data->milk) . "l X " . ($data->rate ?? 0) . ")", 2, $data->grandtotal ?? 0, $lastdate, '108');
                } else {
                    $ledger->addLedger("Payment for milk (" . ($data->milk) . "l X " . ($data->rate ?? 0) . ")", 1, $data->grandtotal ?? 0, $lastdate, '108');
                }
            }

            $farmerreport = new FarmerReport();
            $farmerreport->user_id = $data->id;
            $farmerreport->milk = $data->milk ?? 0;
            $farmerreport->snf = $data->snf ?? 0;
            $farmerreport->fat = $data->fat ?? 0;
            $farmerreport->rate = $data->rate ?? 0;
            $farmerreport->total = $data->total ?? 0;
            $farmerreport->due = $data->due ?? 0;
            $farmerreport->prevdue = $data->prevdue ?? 0;
            $farmerreport->bonus = $data->bonus ?? 0;
            $farmerreport->advance = $data->advance ?? 0;
            $farmerreport->nettotal = $data->nettotal ?? 0;
            $farmerreport->balance = $data->balance ?? 0;
            $farmerreport->tc = $data->tc ?? 0;
            $farmerreport->fpaid = $data->fpaid ?? 0;
            $farmerreport->cc = $data->cc ?? 0;
            $farmerreport->protsahan_amount = $request->protsahan_amount ?? 0;
            $farmerreport->transport_amount = $request->transport_amount ?? 0;
            $farmerreport->grandtotal = $data->grandtotal ?? ($data->total ?? 0);
            $farmerreport->paidamount = $data->paidamount ?? 0;
            $farmerreport->prevbalance = $data->prevbalance ?? 0;
            $farmerreport->year = $request->year;
            $farmerreport->month = $request->month;
            $farmerreport->session = $request->session??1;
            $farmerreport->center_id = $request->center_id;
            $farmerreport->save();
        }

        $sessionwatch = new SessionWatch();
        $sessionwatch->year = $request->year;
        $sessionwatch->month = $request->month;
        $sessionwatch->session = $request->session??1;
        $sessionwatch->center_id = $request->center_id;
        $sessionwatch->save();
        return redirect()->back();
    }

    public function milk(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];

            $milkdatas = DB::table('milkdatas')->join('farmers', 'farmers.user_id', '=', 'milkdatas.user_id')
                ->join('centers', 'centers.id', '=', 'farmers.center_id')
                ->join('users', 'users.id', '=', 'milkdatas.user_id');


            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $milkdatas = $milkdatas->where('milkdatas.date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            }

            $hascenter = false;
            if ($request->center_id != -1) {
                $hascenter = true;
                $milkdatas = $milkdatas->where('farmers.center_id', $request->center_id);
            }

            $datas = $milkdatas->select('milkdatas.m_amount', 'milkdatas.e_amount', 'milkdatas.user_id', 'milkdatas.date', 'farmers.center_id', 'users.name', 'users.no')->get();
            $data1 = $milkdatas->select(DB::raw('sum(milkdatas.m_amount) as m_amount,sum(milkdatas.e_amount) as e_amount ,milkdatas.user_id ,users.name,users.no,farmers.center_id'))->groupBy('milkdatas.user_id', 'users.name', 'users.no', 'farmers.center_id')->get()->groupBy('center_id');



            return view('admin.report.milk.data', compact('data1'));
        } else {
            return view('admin.report.milk.index');
        }
    }

    public function sales(Request $request)
    {
        if ($request->getMethod() == "POST") {
            // dd($request->all());
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];

            $farmersell = DB::table('sellitems')->join('users', 'users.id', '=', 'sellitems.user_id')
                ->join('farmers', 'users.id', '=', 'farmers.user_id')
                ->select(DB::raw('sellitems.user_id,sellitems.total,sellitems.item_id,sellitems.qty,sellitems.rate'));

            $dissell = DB::table('sellitems')->join('users', 'users.id', '=', 'sellitems.user_id')
                ->join('distributers', 'users.id', '=', 'distributers.user_id')
                ->select(DB::raw('sellitems.user_id,sellitems.total,sellitems.item_id,sellitems.qty,sellitems.rate'));

            $empsell = DB::table('sellitems')->join('users', 'users.id', '=', 'sellitems.user_id')
                ->join('employees', 'users.id', '=', 'employees.user_id')
                ->select(DB::raw('sellitems.user_id,sellitems.total,sellitems.item_id,sellitems.qty,sellitems.rate'));

            $countersell = DB::table('bills')->select(DB::raw('id,net_total as total,center_id'));


            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $farmersell = $farmersell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $dissell = $dissell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $empsell = $empsell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $countersell = $countersell->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $farmersell = $farmersell->where('sellitems.date', '=', $date);
                $dissell = $dissell->where('sellitems.date', '=', $date);
                $empsell = $empsell->where('sellitems.date', '=', $date);
                $countersell = $countersell->where('date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $farmersell = $farmersell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $dissell = $dissell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $empsell = $empsell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $countersell = $countersell->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $farmersell = $farmersell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $dissell = $dissell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $empsell = $empsell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $countersell = $countersell->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $farmersell = $farmersell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $dissell = $dissell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $empsell = $empsell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $countersell = $countersell->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $farmersell = $farmersell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $dissell = $dissell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $empsell = $empsell->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $countersell = $countersell->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            }

            if ($request->center_id != -1) {
                $farmersell = $farmersell->where('farmers.center_id', $request->center_id);
            }


            $counterAmount = $countersell->get();
            $farmerAmount = $farmersell->get();
            $disAmount = $dissell->get();
            $empAmount = $empsell->get();


            $counterAmountIDS = $counterAmount->pluck('id');


            $users = DB::table('users')->get(['id', 'name', 'no']);
            $items = DB::table('items')->get(['id', 'title']);
            $centers = DB::table('centers')->get(['id', 'name']);
            // dd($dissell->get(),$farmersell->get(),$empsell->get());
            // dd($farmerAmount,$disAmount,$empAmount,$users);
            $g = [];
            $itm = [];

            $g[0] = $farmerAmount->groupBy('user_id');
            $g[1] = $disAmount->groupBy('user_id');
            $g[2] = $empAmount->groupBy('user_id');
            $g[3] = $counterAmount->groupBy('center_id');

            $itm[0] = $farmerAmount->groupBy('item_id');
            $itm[1] = $disAmount->groupBy('item_id');
            $itm[2] = $empAmount->groupBy('item_id');
            $itm[3] = DB::table('bill_items')->whereIn('bill_id', $counterAmountIDS)
                ->select(DB::raw('id,item_id,qty,total'))->get()->groupBy('item_id');

            $byName = [];
            $byItem = [];

            for ($i = 0; $i < 4; $i++) {
                $byName[$i] = [];
                foreach ($g[$i] as $key => $val) {
                    $localAmount = 0;
                    foreach ($val as $key1 => $value) {
                        $localAmount += $value->total;
                    }
                    if ($i < 3) {

                        $user = $users->where('id', $key)->first();
                        array_push($byName[$i], (object)[
                            'total' => $localAmount,
                            'id' => $user->id,
                            'name' => $user->name,
                            'no' => $user->no,
                        ]);
                    } else {
                        $center = $centers->where('id', $key)->first();
                        array_push($byName[$i], (object)[
                            'total' => $localAmount,
                            'id' => $center->id,
                            'name' => $center->name,
                        ]);
                    }
                }

                $byItem[$i] = [];
                foreach ($itm[$i] as $key => $val) {
                    $localAmount = 0;
                    $localqty = 0;
                    foreach ($val as $key1 => $value) {
                        $localAmount += $value->total;
                        $localqty += $value->qty;
                    }
                    $item = $items->where('id', $key)->first();
                    array_push($byItem[$i], (object)[
                        'total' => $localAmount,
                        'qty' => $localqty,
                        'id' => $item->id,
                        'name' => $item->title,
                    ]);
                }
            }

            $itemAmount = [];
            for ($i = 0; $i < 4; $i++) {
                foreach ($byItem[$i] as $key => $value) {
                    if (!isset($itemAmount['item_' . $value->id])) {
                        $itemAmount['item_' . $value->id] = (object)[
                            'total' => $value->total,
                            'qty' => $value->qty,
                            'id' => $value->id,
                            'name' => $value->name,
                        ];
                    } else {
                        $itemAmount['item_' . $value->id]->qty += $value->qty;
                        $itemAmount['item_' . $value->id]->total += $value->total;
                    }
                }
            }



            $cats = explode(',', env('sales_report_category', ''));
            return view('admin.report.sales.data', compact('byName', 'byItem', 'itemAmount', 'cats'));
        } else {
            return view('admin.report.sales.index');
        }
    }


    // billing sale

    public function posSales(Request $request)
    {
        if ($request->isMethod('post')) {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $type = $request->type;
            $range = [];
            $bill = PosBill::orderBy('id', 'asc');
            $bill_items = PosBillItem::join('pos_bills', 'pos_bills.id', '=', 'pos_bill_items.pos_bill_id');
            $salesReturn_query = CreditNote::orderBy('id', 'asc');
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $bill = $bill->where('date', $date);
                $salesReturn_query = $salesReturn_query->where('date', $date);
                $bill_items = $bill_items->where('pos_bills.date', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);
                $range[2] = str_replace('-', '', $request->date2);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 6) {
                $fiscalYear = FiscalYear::find($request->fiscalYear);
                $range[1] = $fiscalYear->startdate;
                $range[2] = $fiscalYear->enddate;
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            }

            $bills = $bill->orderBy('date', 'asc')->get();
            $billitems = $bill_items->orderBy('pos_bills.date', 'asc')->select(
                DB::raw('pos_bill_items.*,pos_bills.bill_no')
            )->get();

            $saleReturn = $salesReturn_query->select('id', 'bill_no', 'date', 'qty')->get();
            $ddd = $billitems->groupBy('item_id');
            $billItemDatas = [];

            foreach ($ddd as $key => $b) {
                $billItemData = [];
                $billItemData['item_id'] = $key;
                $billItemData['item_name'] = $b->first()->name;
                $billItemData['value'] = [];
                $ssd = $b->groupBy('rate');
                foreach ($ssd as $key1 => $b1) {
                    $sd = [];
                    $sd['rate'] = $key1;
                    $sd['qty'] = $b1->sum('qty');
                    $sd['amount'] = $b1->sum('amount');
                    $sd['discount'] = $b1->sum('discount');
                    $sd['taxable'] = $b1->sum('taxable');
                    $sd['tax'] = $b1->sum('tax');
                    $sd['total'] = $b1->sum('total');
                    // $sd['bi']=$b1;
                    array_push($billItemData['value'], $sd);
                }
                array_push($billItemDatas, $billItemData);
            }


            // dd($billItemDatas);
            $groupData = json_encode(ReportManager::makeGroup($type, $request), JSON_NUMERIC_CHECK);
            // dd($groupData);
            return view('admin.report.billingsale.data', compact('bills', 'billItemDatas', 'type', 'groupData', 'saleReturn'));
        } else {

            return view('admin.report.billingsale.index');
        }
    }




    // distributer

    public function distributor(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $elements = [];
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];
            $date = -1;
            $distributers = Distributer::join('users', 'users.id', '=', 'distributers.user_id')->select(DB::raw('distributers.id,users.id as user_id, users.name'))->get();
            foreach ($distributers as $key => $distributer) {
                $data = Ledger::where('user_id', $distributer->user_id);
                if ($type == 0) {
                    $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 1) {
                    $date = $date = str_replace('-', '', $request->date1);
                    $range[1] = $date;
                    $data = $data->where('date', '=', $date);
                } elseif ($type == 2) {
                    $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 3) {
                    $range = NepaliDate::getDateMonth($request->year, $request->month);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 4) {
                    $range = NepaliDate::getDateYear($request->year);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 5) {
                    $range[1] = str_replace('-', '', $request->date1);;
                    $range[2] = str_replace('-', '', $request->date2);;
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                }
                $element = $distributer->toArray();
                $data1 = clone $data;
                $data2 = clone $data;
                $element['milk'] = $data->where('identifire', '132')->sum('amount');
                $element['total'] = $data1->where('identifire', '103')->sum('amount');
                $element['paid'] = $data2->where('identifire', '114')->sum('amount');

                $element['due'] = 0;
                $element['advance'] = 0;

                $element['prevadvance'] = 0;
                $element['prevdue'] = 0;


                $opening = 0;
                $balance = 0;
                $prev = 0;

                if (env('acc_system', 'old') == 'old') {
                    $prev = Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 2)->sum('amount') -
                        Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 1)->sum('amount');
                } else {
                    $prev = Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 2)->sum('amount') -
                        Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 1)->sum('amount');
                }

                if (env('acc_system', 'old') == 'old') {
                    $element['opening'] = Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])
                        ->where('type', 2)->where('identifire', 119)->sum('amount') -
                        Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])->where('type', 1)->where('identifire', 119)->sum('amount');
                } else {
                    $element['opening'] = Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])
                        ->where('type', 1)->where('identifire', 119)->sum('amount') -
                        Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])->where('type', 2)->where('identifire', 119)->sum('amount');
                }

                $prev +=  $element['opening'];
                if ($prev > 0) {
                    $element['prevadvance'] = $prev;
                    $element['prevdue'] = 0;
                } else {
                    $element['prevadvance'] = 0;
                    $element['prevdue'] = (-1) * $prev;
                }

                $element['total'] = $element['total'] + $element['milk'];
                $balance = $prev - $element['total'] + $element['paid'];

                if ($balance > 0) {
                    $element['advance'] = $balance;
                    $element['due'] = 0;
                } else {
                    $element['advance'] = 0;
                    $element['due'] = (-1) * $balance;
                }

                array_push($elements, $element);
            }

            // dd($elements);
            return view('admin.report.distributor.data', compact('elements'));
        } else {
            return view('admin.report.distributor.index');
        }
    }

    public function employee(Request $request)
    {
        if ($request->getMethod() == "POST") {
            // dd($request->all());

            $year = $request->year;
            $month = $request->month;
            $range = NepaliDate::getDateMonth($year, $month);
            $employees = DB::select("select e.id,e.user_id,u.name,e.salary,e.start,e.enddate,e.acc
            from employees e
            join users u on e.user_id=u.id
            where e.enddate>={$range[1]} or e.enddate is null order by e.id asc");
            // dd($employees);
            // $employees = DB::table('employees')
            // ->where('enddate', '<=', $range[2])->orWhereNull('enddate')->get();
            // dd($employees,DB::table('employees')->where('enddate', '<=', $range[2])->orWhereNull('enddate')->toSql());
            $data = [];
            foreach ($employees as $employee) {

                $employee->prevbalance  = Ledger::where('date', '<', $range[1])->where('type', 2)->where('user_id', $employee->user_id)->sum('amount')
                    - Ledger::where('date', '<', $range[1])->where('type', 1)->where('user_id', $employee->user_id)->sum('amount')
                    +Ledger::where('date', '<=', $range[2])->where('date', '>=', $range[1])->where('type', 2)->where('identifire',303)->where('user_id', $employee->user_id)->sum('amount')
                    -Ledger::where('date', '<=', $range[2])->where('date', '>=', $range[1])->where('type', 1)->where('identifire',303)->where('user_id', $employee->user_id)->sum('amount')
                    ;

                $employee->advance = EmployeeAdvance::where('employee_id', $employee->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount')
                    +  Ledger::where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('user_id', $employee->user_id)->where('identifire', 301)->sum('amount');
                
                $employee->salary = NepaliDate::calculateSalary($year, $month, $employee);
                $employee->paid = Ledger::where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('user_id', $employee->user_id)->where('identifire', 124)->sum('amount');
                $employee->returned = Ledger::where('date', '>=', $range[1])
                    ->where('date', '<=', $range[2])
                    ->where('user_id', $employee->user_id)
                    ->where('identifire', 140)->sum('amount') +
                    Ledger::where('date', '>=', $range[1])
                    ->where('date', '<=', $range[2])
                    ->where('user_id', $employee->user_id)
                    ->where('identifire', 302)
                    ->sum('amount');

                $employee->old = false;

                array_push($data, $employee);
            }
            // dd($data);
            // $advance=EmployeeAdvance::where
            return view('admin.report.employee.data', compact('data', 'year', 'month'));
        } else {
            return view('admin.report.employee.index');
        }
    }

    public function employeeSession(Request $request)
    {
        foreach ($request->employees as $employee) {
            $report = new EmployeeReport();
            $report->employee_id = $employee->id;
            $report->prebalance = $employee->prevbalance;
            $report->advance = $employee->advance;
            $report->salary = $employee->salary;
            $report->save();
        }
        return redirect()->back();
    }



    public function credit()
    {

        $farmercredit = \App\Models\User::where('role', 1)->where('amount', '>', 0)->where('amounttype', 1)->get();
        $distributorcredit = \App\Models\User::where('role', 2)->where('amount', '>', 0)->where('amounttype', 1)->get();
        return view('admin.report.credit.index', compact('farmercredit', 'distributorcredit'));
    }


    public function expense(Request $request)
    {
        if ($request->isMethod('post')) {
            $type = $request->type;
            $range = [];
            $data = [];
            $data = DB::table('expenses')->join('expcategories', 'expcategories.id', '=', 'expenses.expcategory_id');
            $billExpenses = DB::table('bill_expenses')
                ->join('supplierbills', 'supplierbills.id', '=', 'bill_expenses.supplierbill_id')
                ->join('users', 'supplierbills.user_id', '=', 'users.id');
            if ($type == 0) {
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $data = $data->where('date', '=', $date);
                $billExpenses = $billExpenses->where('supplierbills.date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $billExpenses = $billExpenses->where('supplierbills.date', '>=', $range[1])->where('supplierbills.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $billExpenses = $billExpenses->where('supplierbills.date', '>=', $range[1])->where('supplierbills.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $billExpenses = $billExpenses->where('supplierbills.date', '>=', $range[1])->where('supplierbills.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $billExpenses = $billExpenses->where('supplierbills.date', '>=', $range[1])->where('supplierbills.date', '<=', $range[2]);
            }



            $purchaseExp = [];
            $alldata = [];
            if ($request->category_id > 0) {
                $hascat = true;
                $data = $data->where('expcategory_id', $request->category_id);
                $alldata = $data->select(DB::raw('expenses.*,expcategories.name'))->get()->groupBy('name');
            } else if ($request->category_id == 0) {
                $purchaseExp = $billExpenses->select(DB::raw('bill_expenses.*,supplierbills.date, supplierbills.billno,users.name '))->get();
            } else {
                $alldata = $data->select(DB::raw('expenses.*,expcategories.name'))->get()->groupBy('name');
                $purchaseExp = $billExpenses->select(DB::raw('bill_expenses.*,supplierbills.date, supplierbills.billno,users.name '))->get();
            }


            return view('admin.report.expense.data', compact('alldata', 'purchaseExp'));
        } else {
            return view('admin.report.expense.index');
        }
    }

    public function bonus(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();

            $year1 = $request->year1;
            $year2 = $request->year2;
            $month1 = $request->month1;
            $month2 = $request->month2;
            $f_data = [];
            $timer = 1;

            foreach ($farmers as $key => $farmer) {
                $timer = 1;
                $semi = true;
                $_year1 = $year1;
                $_year2 = $year2;
                $_month1 = $month1;
                $_month2 = $month2;
                $data = [];
                $session = 1;
                $sum = 0;
                while ($semi) {
                    $_bonus = FarmerReport::where([
                        ['year', $_year1],
                        ['month', $_month1],
                        ['session', $session],
                        ['user_id', $farmer->id]
                    ])->sum('bonus');
                    array_push($data, [$_year1, $_month1, $session, $_bonus]);
                    $sum += $_bonus;
                    $session += 1;
                    $timer += 1;
                    if ($session > 2) {
                        $_month1 += 1;
                        $session = 1;
                    }
                    if ($_month1 > 12) {
                        $_year1 += 1;
                        $_month1 = 1;
                    }
                    $semi = (($_year1 * 10000) + ($_month1) * 100) <= (($_year2 * 10000) + ($_month2) * 100);
                }
                $farmer->data = $data;
                $farmer->sum = $sum;
                array_push($f_data, $farmer);
            }
            // dd($f_data);
            return view('admin.report.bonus.data', ['farmers' => $f_data, 'detailed' => $request->detailed, 'times' => $timer]);
        } else {
            return view('admin.report.bonus.index');
        }
    }

    public function bonus1(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();

            $year1 = $request->year1;
            $year2 = $request->year2;
            $month1 = $request->month1;
            $month2 = $request->month2;
            $f_data = [];
            $timer = 1;

            foreach ($farmers as $key => $farmer) {
                $timer = 1;
                $semi = true;
                $_year1 = $year1;
                $_year2 = $year2;
                $_month1 = $month1;
                $_month2 = $month2;
                $data = [];
                $session = 1;
                $sum = 0;
                while ($semi) {
                    $range = $range = NepaliDate::getDate($_year1, $_month1, $session);
                    $_bonus = $this->getBonus($farmer->id, $range);
                    // array_push($data,[$_year1,$_month1,$session,$_bonus]);
                    $sum += $_bonus;
                    $session += 1;
                    $timer += 1;
                    if ($session > 2) {
                        $_month1 += 1;
                        $session = 1;
                    }
                    if ($_month1 > 12) {
                        $_year1 += 1;
                        $_month1 = 1;
                    }
                    $semi = (($_year1 * 10000) + ($_month1) * 100) <= (($_year2 * 10000) + ($_month2) * 100);
                }
                // $farmer->data=$data;
                $farmer->sum = $sum;
                array_push($f_data, $farmer);
            }
            // dd($f_data);
            return view('admin.report.bonus.data', ['farmers' => $f_data, 'detailed' => $request->detailed, 'times' => $timer]);
        } else {
            return view('admin.report.bonus.index');
        }
    }

    public function getBonus($user_id, $range)
    {
        $farmer1 = User::find($user_id);


        $snfAvg = truncate_decimals(Snffat::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->avg('snf'), 2);
        $fatAvg = truncate_decimals(Snffat::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->avg('fat'), 2);

        $center = Center::where('id', $farmer1->farmer()->center_id)->first();

        $fatAmount = ($fatAvg * $center->fat_rate);
        $snfAmount = ($snfAvg * $center->snf_rate);

        $farmer1->snf = $snfAvg;
        $farmer1->fat = $fatAvg;
        if ($farmer1->farmer()->userate == 1) {

            $farmer1->rate = $farmer1->farmer()->rate;
        } else {

            $farmer1->rate = truncate_decimals($fatAmount + $snfAmount);
        }

        $farmer1->milk = Milkdata::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('e_amount') + Milkdata::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('m_amount');

        $farmer1->totalamount = truncate_decimals(($farmer1->rate * $farmer1->milk), 2);

        $farmer1->tc = 0;
        $farmer1->cc = 0;


        if ($farmer1->farmer()->usetc == 1 && $farmer1->totalamount > 0) {
            $farmer1->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer1->milk), 2);
        }
        if ($farmer1->farmer()->usecc == 1 && $farmer1->totalamount > 0) {
            $farmer1->cc = truncate_decimals($center->cc * $farmer1->milk, 2);
        }


        $farmer1->grandtotal = (int)($farmer1->totalamount + $farmer1->tc + $farmer1->cc);
        $farmer1->bonus = 0;
        if (env('hasextra', 0) == 1) {
            $farmer1->bonus = (int)($farmer1->grandtotal * $center->bonus / 100);
            return $farmer1->bonus;
        } else {
            return 0;
        }
    }

    public function stock(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $datas = [];
            $center = '';
            if ($request->center_id == -1) {
                $datas = DB::select('select id,title,
                (select sum(amount) from center_stocks where center_stocks.item_id=items.id) as qty,
                greatest((select sum(amount*rate) from center_stocks where center_stocks.item_id=items.id),0) as current_stock
                from items');
            } else {
                $datas = DB::select('select id,title,
                (select sum(amount) from center_stocks where center_stocks.item_id=items.id and center_stocks.center_id=' . $request->center_id . ') as qty,
                greatest((select sum(amount*rate) from center_stocks where center_stocks.item_id=items.id and center_stocks.center_id=' . $request->center_id . '),0) as current_stock
                from items');
                $center == Center::where('id', $request->center_id)->first(['name'])->name;
            }

            // dd($datas);

            return view('admin.report.stock.data', compact('datas', 'center'));
        } else {
            return view('admin.report.stock.index');
        }
    }
}
