<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Advance;
use App\Models\Center;
use App\Models\FarmerReport;
use App\Models\Ledger;
use App\Models\MilkPayment;
use App\Models\User;
use App\NepaliDate;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerDetailController extends Controller
{
    public function index()
    {
        // dd(renderCenters());
        return view('admin.farmer.passbook.index');
    }

    public function updateData(Request $request){
            $date = str_replace('-', '', $request->date);;
            $farmerreport=FarmerReport::where('id',$request->report_id)->first();
            $oldbonus=0;
            $oldgrandtotal=0;
            if($farmerreport==null){
                throw new \Exception("Session Not Closed");
            }else{
                $oldbonus=$farmerreport->bonus;
                $oldgrandtotal=$farmerreport->grandtotal;
            }
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
            $farmerreport->grandtotal = $request->grandtotal ?? $request->total;
            $farmerreport->fpaid = $request->fpaid;
            $farmerreport->has_passbook = !($request->filled('no_passbook'));
            $farmerreport->save();

            $ledger=new LedgerManage($request->id);
            if (env('hasextra', 0) == 1) {

                if($oldbonus!=$farmerreport->bonus ){
    
                    $ledger_bonus=Ledger::where([
                        'year' => $farmerreport->year, 
                        'month' => $farmerreport->month,
                        'session' => $farmerreport->session,
                        'user_id' => $request->id,
                        'identifire'=>124
                      ])->first();
                    if($ledger_bonus!=null){
                        
                        $ledger_bonus->amount=$farmerreport->bonus;
                        $ledger_bonus->save();
                    }else{
                        if (env('acc_system', "old") == "old") {
                            $ledger->addLedger("Bonus", 1, $request->bonus, $date, '124');

                        }else{
                            $ledger->addLedger("Bonus", 2, $request->bonus, $date, '124');

                        }
                    }
                }
            }

            if($oldgrandtotal != $farmerreport->grandtotal){
                $ledger_grandtotal=Ledger::where([
                    'year' => $farmerreport->year, 
                    'month' => $farmerreport->month,
                    'session' => $farmerreport->session,
                    'user_id' => $request->id,
                    'identifire'=>108
                  ])->first();
                  if($ledger_grandtotal!=null){
                    $ledger_grandtotal->title="Payment for milk (" . ($request->milk) . "l)";
                    $ledger_grandtotal->amount=$request->grandtotal;
                    $ledger_grandtotal->save();
                  }else{
                      if (env('acc_system', "old") == "old") {
                         
                          if ($request->grandtotal > 0) {
                              $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 2, $request->grandtotal, $date, '108');
                          }
                      } else {
                         
                          if ($request->grandtotal > 0) {
                              $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 1, $request->grandtotal, $date, '108');
                          }
                      }
                  }
            }

    }

    public function data(Request $request)
    {
        $farmer = DB::table('users')->join('farmers', 'users.id', '=', 'farmers.user_id')->
        where('users.no', $request->farmer_no)->where('farmers.center_id', $request->center_id)->
        select(DB::raw( 'users.id,users.name,users.no,users.phone,farmers.userate,farmers.usecc,farmers.usetc,farmers.rate'))->first();
        $center=DB::selectOne('select snf_rate,fat_rate,cc,tc,bonus from centers where id=?',[$request->center_id]);
        
        $farmer->session=[$request->year, $request->month, $request->session];
        $farmer->center_id=$request->center_id;
        $range = NepaliDate::getDate($request->year, $request->month, $request->session);

        $farmer->report = FarmerReport::where(['year' => $request->year, 'month' => $request->month, 'session' => $request->session, 'user_id' => $farmer->id])->first();
        $farmer->old = $farmer->report!=null;

        $farmer->milkData = DB::table('milkdatas')
        ->where('user_id', $farmer->id)
        ->where('date', '>=', $range[1])
        ->where('date', '<=', $range[2])
        ->select(DB::raw('id,e_amount,m_amount,date'))
        ->orderBy('date')
        ->get();
        $farmer->snfFats = DB::table('snffats')
        ->where('user_id', $farmer->id)
        ->where('date', '>=', $range[1])
        ->where('date', '<=', $range[2])
        ->select(DB::raw('id,snf,fat,date'))
        ->orderBy('date')
        ->get();

        $snfAvg = truncate_decimals($farmer->snfFats->avg('snf'), 2);
        $fatAvg = truncate_decimals($farmer->snfFats->avg('fat'), 2);


        $fatAmount = ($fatAvg * $center->fat_rate);
        $snfAmount = ($snfAvg * $center->snf_rate);

        $farmer->snfavg = $snfAvg;
        $farmer->fatavg = $fatAvg;

        $hasRate=false;
        $farmer->milkamount = $farmer->milkData->sum('e_amount') + $farmer->milkData->sum('m_amount');
        $farmer->tc = 0;
        $farmer->cc = 0;
        if($farmer->report!=null){
            if($farmer->report->has_passbook==1){
                $hasRate=true;
                $farmer->milkrate = $farmer->report->rate;
                $farmer->cc = $farmer->report->cc;
                $farmer->tc = $farmer->report->tc;
            }
        }
        if(!$hasRate){

            if ($farmer->userate == 1) {
    
                $farmer->milkrate = $farmer->rate;
            } else {
    
                $farmer->milkrate = truncate_decimals($fatAmount + $snfAmount);
            }
           
    
    
            if ($farmer->usetc == 1 && $farmer->total > 0) {
                $farmer->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer->milkamount), 2);
            }
            if ($farmer->usecc == 1 && $farmer->total > 0) {
                $farmer->cc = truncate_decimals($center->cc * $farmer->milkamount, 2);
            }
        }
        
        $farmer->total = truncate_decimals(($farmer->milkrate * $farmer->milkamount), 2);

        $farmer->fpaid = ledgerSum($farmer->id, '106', $range) + ledgerSum($farmer->id, '107', $range);

        $farmer->grandtotal = (int)($farmer->total + $farmer->tc + $farmer->cc);
        $farmer->bonus = 0;

        if (env('hasextra', 0) == 1) {
            $farmer->bonus = (int)($farmer->grandtotal * $center->bonus / 100);
        }

        $farmer->purchase = ledgerSum($farmer->id, '103', $range);

        // $previousMonth1 = Ledger::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 1)->sum('amount');
        // $previousBalance = Ledger::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 2)->sum('amount');

        $previousMonth1 = 0;
        $previousBalance = 0;

        $closing = 0;
        $arr = [];
        $prev = ledgerPrev($farmer->id, $range[1], 2) - ledgerPrev($farmer->id, $range[1], 1);

        $previousMonth = ledgerSum($farmer->id, '101', $range, 2) - ledgerSum($farmer->id, '101', $range, 1)
        + ledgerSum($farmer->id, '102', $range, 2) - ledgerSum($farmer->id, '102', $range, 1);
        $base = $prev;

        $ledgers = Ledger::where('user_id', $farmer->id)
            ->where('date', '>=', $range[1])
            ->where('date', '<=', $range[2])
            ->where('identifire', '!=', 109)->
            where('identifire', '!=', 120)->
            orderBy('date', 'asc')->
            orderBy('id', 'asc')->get();

        // array_push($arr,(object)[
        //     'title'=>'Previous Balance	'
        // ]);
        foreach ($ledgers as $key => $l) {
            if ($l->type == 1) {
                $base -= $l->amount;
            } else {
                $base += $l->amount;
            }
            $l->amt = $base;
            $closing = $base;
            array_push($arr, $l);
        }
        $farmer->ledger = $arr;
        $prevtotal=$prev+$previousMonth;

        // dd($prev,$previousMonth,$prevtotal);
        if ($prevtotal < 0) {
            $previousBalance = -1 * $prevtotal;
        } else {
            $previousMonth1 = $prevtotal;
        }


        $farmer->advance = (float)(Advance::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount'));
        $farmer->prevdue = (float)$previousMonth1;
        $farmer->prevbalance = (float)$previousBalance;
        $farmer->paidamount = (float)Ledger::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '121')->sum('amount');
        $balance = $farmer->grandtotal - $farmer->prevdue - $farmer->advance - $farmer->purchase - $farmer->paidamount + $farmer->prevbalance - $farmer->bonus + $farmer->fpaid;

        $farmer->balance = 0;
        $farmer->nettotal = 0;
        if ($balance < 0) {
            $farmer->balance = (-1) * $balance;
        }
        if ($balance > 0) {
            $farmer->nettotal = $balance;
        }

        // $farmer->ledger = Ledger::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->orderBy('id', 'asc')->get();
        // dd($milk_rate);
        // dd(compact('snfFats','milkData','data','center','farmer1'));
        $closingDate = NepaliDate::getDateSessionLast($request->year, $request->month, $request->session);
        return view('admin.farmer.passbook.data',compact('farmer','closingDate','prev','closing','center'));
        // dd($farmer,$closingDate);
    }

    public function close(Request $request){
        $date = str_replace('-', '', $request->date);;
        $ledger = new LedgerManage($request->id);
        $data=[];
        if($request->filled('payment_amount')){
            if($request->payment_amount>0){

                $payment=new MilkPayment();
                $payment->session=$request->session;
                $payment->year=$request->year;
                $payment->month=$request->month;
                $payment->center_id=$request->center_id;
                $payment->amount=$request->payment_amount;
                $payment->user_id=$request->id;
                $payment->date=$date;
                $payment->save();
               
                $ledger=new LedgerManage($payment->user_id);
                if(env('acc_system','old')=='old'){
                    $ledger->addLedger('Payment Milk Payment Given To Farmer',1,$payment->amount,$date,'121',$payment->id);
                }else{
                    $ledger->addLedger('Payment Milk Payment Given To Farmer',2,$payment->amount,$date,'121',$payment->id);
                }

                if($request->filled('passbookchecked')){
                    DB::update('update farmer_reports set has_passbook=1 where user_id=? and year=? and month= ? and session=? ',[$request->id,$request->year,$request->month,$request->session]);
                }
                new PaymentManager($request,$payment->id,121);
                array_push($data,$payment);
            }
        }
        if($request->filled('close')){
            if (env('acc_system', "old") == "old") {
                if (env('hasextra', 0) == 1) {
                    $ledger->addLedger("Bonus", 1, $request->bonus, $date, '124');
                }
                if ($request->grandtotal > 0) {
                    $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 2, $request->grandtotal, $date, '108');
                }
            } else {
                if (env('hasextra', 0) == 1) {
                    $ledger->addLedger("Bonus", 2, $request->bonus, $date, '124');
                }
                if ($request->grandtotal > 0) {
                    $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 1, $request->grandtotal, $date, '108');
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
            $farmerreport->grandtotal = $request->grandtotal ?? $request->total;
            $farmerreport->year = $request->year;
            $farmerreport->month = $request->month;
            $farmerreport->session = $request->session;
            $farmerreport->fpaid = $request->fpaid;
            $farmerreport->center_id = $request->center_id;
            $farmerreport->has_passbook = !($request->filled('no_passbook'));
            $farmerreport->save();
            array_push($data,$farmerreport);
        }

      

        return response()->json($data);
    }

    public function closeNotClosed(Request $request){
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
            $farmerreport->grandtotal = $data->grandtotal ?? ($data->total ?? 0);
            $farmerreport->paidamount = $data->paidamount ?? 0;
            $farmerreport->prevbalance = $data->prevbalance ?? 0;
            $farmerreport->year = $request->year;
            $farmerreport->month = $request->month;
            $farmerreport->session = $request->session;
            $farmerreport->center_id = $request->center_id;
            $farmerreport->has_passbook = 0;
            $farmerreport->save();
        }

        return response()->json(["status"=>true]);
    }

    public function notClosed(Request $request){
        if($request->getMethod()=="POST"){
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $center = Center::find($request->center_id);
            $year = $request->year;
            $month = $request->month;
            $session = $request->session;
            $usetc = (env('usetc', 0) == 1) && ($center->tc > 0);
            $usecc = (env('usecc', 0) == 1) && ($center->cc > 0);
            $user_ids=DB::table('users')->join('farmers','farmers.user_id','=','users.id')->where('farmers.center_id',$center->id)->orderBy('users.id')->pluck('users.id')->toArray();
            $reports_id= DB::table('farmer_reports')->where(['year' => $year, 'month' => $month, 'session' => $session])->whereIn('user_id',$user_ids)->orderBy('user_id')->pluck('user_id')->toArray();
            $remain=array_diff($user_ids,$reports_id);
            if(count($remain)==0){
                return response("Session Closed Of All Farmers");
            }
            $remainList="(". implode(",",$remain).")";

            // dd($user_ids,$reports_id,$remainList);

            $query = "select  u.id,u.no,u.name,u.usecc,u.rate,u.usetc,u.userate,
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
            from (select iu.name,iu.id,iu.no,f.usecc,f.rate,f.usetc,f.userate from users iu join farmers f on iu.id=f.user_id where f.center_id={$center->id} and iu.id in {$remainList}  ) u order by u.no asc";

            $farmers = DB::select($query);

         
            $datas = [];
         

            foreach ($farmers as $key => $farmer) {
                
                $farmer->fat = truncate_decimals($farmer->fat);
                $farmer->snf = truncate_decimals($farmer->snf);
                $fatAmount = ($farmer->fat * $center->fat_rate);
                $snfAmount = ($farmer->snf * $center->snf_rate);
                if ($farmer->userate == 1) {

                    $farmer->rate = $farmer->rate;
                } else {

                    $farmer->rate = truncate_decimals($fatAmount + $snfAmount);
                }

                $farmer->total = truncate_decimals(($farmer->rate * $farmer->milk), 2);

                $farmer->tc = 0;
                $farmer->cc = 0;

                if ($farmer->usetc == 1 && $farmer->total > 0) {
                    $farmer->tc = truncate_decimals((($center->tc * ($farmer->snf + $farmer->fat) / 100) * $farmer->milk), 2);
                }
                if ($farmer->usecc == 1 && $farmer->total > 0) {
                    $farmer->cc = truncate_decimals($center->cc * $farmer->milk, 2);
                }
                $farmer->bonus = 0;
                if (env('hasextra', 0) == 1) {
                    $farmer->bonus = (int)($farmer->grandtotal * $center->bonus / 100);
                }

                $farmer->grandtotal = (int)($farmer->total + $farmer->tc + $farmer->cc);
                $prev = $farmer->prevdr - $farmer->prevcr;
                $opening = $farmer->openingdr - $farmer->openingcr;
                $farmer->prevTotal = $prev + $opening;



                if ($farmer->prevTotal > 0) {
                    $farmer->prevdue = $farmer->prevTotal;
                    $farmer->prevbalance = 0;
                } else {
                    $farmer->prevdue = 0;
                    $farmer->prevbalance = (-1) * $farmer->prevTotal;
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
                array_push($datas,$farmer);
            }
            $closingDate = NepaliDate::getDateSessionLast($request->year, $request->month, $request->session);

            
           return view('admin.farmer.passbook.notclosed.data',compact('datas','year','month','session','center','usecc','usetc',"closingDate"));
        }else{
            return view('admin.farmer.passbook.notclosed.index',[
                'centers'=>DB::table('centers')->get(['id','name'])
            ]);
        }
    }
}
