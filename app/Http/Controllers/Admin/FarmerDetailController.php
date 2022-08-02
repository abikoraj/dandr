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
    public function data(Request $request)
    {
        $farmer = DB::table('users')->join('farmers', 'users.id', '=', 'farmers.user_id')->
        where('users.no', $request->farmer_no)->where('farmers.center_id', $request->center_id)->
        select(DB::raw( 'users.id,users.name,users.no,users.phone,farmers.userate,farmers.usecc,farmers.usetc,farmers.rate'))->first();
        $center=DB::selectOne('select snf_rate,fat_rate,cc,tc,bonus from centers where id=?',[$request->center_id]);
        
        $farmer->session=[$request->year, $request->month, $request->session];
        $farmer->center_id=$request->center_id;
        $range = NepaliDate::getDate($request->year, $request->month, $request->session);

        $farmer->old = FarmerReport::where(['year' => $request->year, 'month' => $request->month, 'session' => $request->session, 'user_id' => $farmer->id])->count() > 0;

        $farmer->milkData = DB::table('milkdatas')
        ->where('user_id', $farmer->id)
        ->where('date', '>=', $range[1])
        ->where('date', '<=', $range[2])
        ->select(DB::raw('id,e_amount,m_amount,date'))
        ->get();
        $farmer->snfFats = DB::table('snffats')
        ->where('user_id', $farmer->id)
        ->where('date', '>=', $range[1])
        ->where('date', '<=', $range[2])
        ->select(DB::raw('id,snf,fat,date'))
        ->get();

        $snfAvg = truncate_decimals($farmer->snfFats->avg('snf'), 2);
        $fatAvg = truncate_decimals($farmer->snfFats->avg('fat'), 2);


        $fatAmount = ($fatAvg * $center->fat_rate);
        $snfAmount = ($snfAvg * $center->snf_rate);

        $farmer->snfavg = $snfAvg;
        $farmer->fatavg = $fatAvg;

        if ($farmer->userate == 1) {

            $farmer->milkrate = $farmer->rate;
        } else {

            $farmer->milkrate = truncate_decimals($fatAmount + $snfAmount);
        }

        $farmer->milkamount = $farmer->milkData->sum('e_amount') + $farmer->milkData->sum('m_amount');
        $farmer->total = truncate_decimals(($farmer->milkrate * $farmer->milkamount), 2);
        $farmer->tc = 0;
        $farmer->cc = 0;


        if ($farmer->usetc == 1 && $farmer->total > 0) {
            $farmer->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer->milkamount), 2);
        }
        if ($farmer->usecc == 1 && $farmer->total > 0) {
            $farmer->cc = truncate_decimals($center->cc * $farmer->milkamount, 2);
        }

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
        $balance = $farmer->grandtotal + $farmer->prevdue - $farmer->advance - $farmer->purchase - $farmer->paidamount + $farmer->prevbalance - $farmer->bonus + $farmer->fpaid;

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
        return view('admin.farmer.passbook.data',compact('farmer','closingDate','prev','closing'));
        // dd($farmer,$closingDate);
    }

    public function close(Request $request){
        $date = str_replace('-', '', $request->date);;
        $ledger = new LedgerManage($request->id);
        $data=[];
        if($request->filled('payment_amount')){
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
            new PaymentManager($request,$payment->id,121);
            array_push($data,$payment);
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
            $farmerreport->save();
            array_push($data,$farmerreport);
        }

      

        return response()->json($data);
    }
}
