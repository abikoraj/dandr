<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Advance;
use App\Models\Center;
use App\Models\FarmerReport;
use App\Models\Ledger;
use App\Models\Milkdata;
use App\Models\Sellitem;
use App\Models\Snffat;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FarmerDashboardController extends Controller
{
    public function index(){
        $mMilk = Milkdata::where('user_id',Auth::user()->id)->sum('m_amount');
        $eMilk = Milkdata::where('user_id',Auth::user()->id)->sum('e_amount');
        $totalMilk = $mMilk+$eMilk;
        $purchase = Sellitem::where('user_id',Auth::user()->id)->sum('total');
        $due = User::where('id',Auth::user()->id)->where('amounttype',1)->sum('amount');
        $paid = User::where('id',Auth::user()->id)->where('amounttype',2)->sum('amount');
        // dd($paid);
        return view('users.farmer.indenx',compact('totalMilk','purchase','due'));
    }



    public function changePassword(Request $request){
        $request->validate([
            'n_pass' =>'required|min:8'
            ],
            [
            'n_pass.min' => 'Password should be at least 8 characters !'
        ]);
        $user = User::where('id',Auth::user()->id)->where('role',1)->first();
       if(Hash::check($request->c_pass, $user->password)){
          $user->password = bcrypt($request->n_pass);
          $user->save();
          return redirect()->back()->with('message','Password changed successfully !');
       }else{
        return redirect()->back()->with('message_danger','Current password does not matched !');
       }
    }

    public function transactionDetail(){
        return view('users.farmer.transaction');
    }

    public function changePasswordPage(){
        return view('users.farmer.password');
    }

    public function loadData(Request $request)
    {
        $monthSession=env('session_type',1)==2;
        $farmer = DB::table('users')->join('farmers', 'users.id', '=', 'farmers.user_id')->
        where('users.id', Auth::user()->id)
        ->select(DB::raw( 'farmers.center_id,users.id,users.name,users.no,users.phone,farmers.userate,farmers.usecc,farmers.usetc,farmers.rate,farmers.ts_amount,farmers.use_ts_amount,farmers.use_protsahan,farmers.protsahan,farmers.use_transport,farmers.transport,farmers.use_custom_rate,farmers.snf_rate,farmers.fat_rate'))->first();
        if($farmer==null){
            return response("<h5 class='text-center'>Farmer Not Found</h5>");
        }

        $center=DB::selectOne('select snf_rate,fat_rate,cc,tc,bonus from centers where id=?',[$farmer->center_id]);
        
        $farmer->session=[$request->year, $request->month, $request->session];
        $farmer->center_id=$request->center_id;
        $range = NepaliDate::getDate($request->year, $request->month, $request->session);
        
        if($monthSession){
            $farmer->report = FarmerReport::where(['year' => $request->year, 'month' => $request->month, 'user_id' => $farmer->id])->first();
        }else{
            $farmer->report = FarmerReport::where(['year' => $request->year, 'month' => $request->month, 'session' => $request->session, 'user_id' => $farmer->id])->first();

        }
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

        if($farmer->use_custom_rate){
            $fatAmount = ($fatAvg * $farmer->fat_rate);
            $snfAmount = ($snfAvg * $farmer->snf_rate);
        }else{
            $fatAmount = ($fatAvg * $center->fat_rate);
            $snfAmount = ($snfAvg * $center->snf_rate);
        }
       

        $farmer->snfavg = $snfAvg;
        $farmer->fatavg = $fatAvg;

        $hasRate=false;
        $farmer->milkamount = $farmer->milkData->sum('e_amount') + $farmer->milkData->sum('m_amount');
        $farmer->tc = 0;
        $farmer->cc = 0;
        $farmer->protsahan_amount=0;
        $farmer->transport_amount=0;
        if($farmer->report!=null){
            if($farmer->report->has_passbook==1){
                $hasRate=true;
                $farmer->milkrate = $farmer->report->rate;
                $farmer->cc = $farmer->report->cc;
                $farmer->tc = $farmer->report->tc;                        
                $farmer->protsahan_amount=$farmer->report->protsahan_amount;
                $farmer->transport_amount=$farmer->report->transport_amount;

            }
        }
        if(!$hasRate){

            if ($farmer->userate == 1) {
    
                $farmer->milkrate = $farmer->rate;
            } else {
    
                $farmer->milkrate = truncate_decimals($fatAmount + $snfAmount);
            }
           
    
            $farmer->total = truncate_decimals(($farmer->milkrate * $farmer->milkamount), 2);
    
            if ($farmer->usetc == 1  && $farmer->total > 0) {
                $farmer->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer->milkamount), 2);
            }
            if($farmer->use_ts_amount==1 && $farmer->total > 0){
                $farmer->tc = truncate_decimals((($farmer->ts_amount) * $farmer->milkamount), 2);
            }
            if ($farmer->usecc == 1 && $farmer->total > 0) {
                $farmer->cc = truncate_decimals($center->cc * $farmer->milkamount, 2);
            }

            if ($farmer->use_protsahan == 1 && $farmer->total > 0) {
                $farmer->protsahan_amount = truncate_decimals($farmer->protsahan * $farmer->milkamount, 2);
            }
            if ($farmer->use_transport == 1 && $farmer->total > 0) {
                $farmer->transport_amount = truncate_decimals($farmer->transport * $farmer->milkamount, 2);
            }
        }else{
            $farmer->total = truncate_decimals(($farmer->milkrate * $farmer->milkamount), 2);

        }
        

        $farmer->fpaid = ledgerSum($farmer->id, '106', $range) + ledgerSum($farmer->id, '107', $range);

        $farmer->grandtotal = (int)($farmer->total + $farmer->tc + $farmer->cc+ $farmer->protsahan_amount+$farmer->transport_amount);
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

        // if($ledgers->where('identifire',108)->count()==0 && env('farmer_detail_milk_ledger',0)==1){
        //     $newLedger=new Ledger();
            
        //     $ledgers->push($newLedger);
        // }
        $milkloaded=$ledgers->where('identifire',108)->count()>0;
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


        $farmer->jinsipaid=(float)Ledger::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '408')->sum('amount');
        $farmer->jinsipurchase=(float)Ledger::where('user_id', $farmer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '409')->sum('amount');
       
        $farmer->paidamount += $farmer-> jinsipurchase;
        $farmer->fpaid += $farmer->jinsipaid;

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
        // dd($farmer);
        // dd(compact('snfFats','milkData','data','center','farmer1'));
    
        $closingDate = NepaliDate::getDateSessionLast($request->year, $request->month, $request->session);
        return view('users.farmer.data',compact('farmer','closingDate','prev','closing','center','milkloaded'));
        // dd($farmer,$closingDate);
    }
    public function loadDataOld(Request $r){
        // dd($r->all());
        $range=NepaliDate::getDate($r->year,$r->month,$r->session);
        $data=$r->all();
        $farmer1=User::where('id',$r->user_id)->first();
        $center = Center::where('id',$farmer1->farmer()->center_id)->first();

        $farmer1->old=FarmerReport::where(['year'=>$r->year,'month'=>$r->month,'session'=>$r->session,'user_id'=>$r->user_id])->count()>0;

        $sellitem = Sellitem::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->get();
        $milkData = Milkdata::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->get();
        $snfFats = Snffat::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->get();

        $snfAvg = truncate_decimals(Snffat::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->avg('snf'),2);
        $fatAvg = truncate_decimals(Snffat::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->avg('fat'),2);


        $fatAmount = ($fatAvg * $center->fat_rate);
        $snfAmount = ($snfAvg * $center->snf_rate);

        $farmer1->snfavg=$snfAvg;
        $farmer1->fatavg=$fatAvg;
        if($farmer1->farmer()->userate==1){

            $farmer1->milkrate=$farmer1->farmer()->rate;
        }else{

            $farmer1->milkrate=truncate_decimals( $fatAmount + $snfAmount);
        }

        $farmer1->milkamount=Milkdata::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->sum('e_amount')+Milkdata::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->sum('m_amount');

        $farmer1->total = truncate_decimals(($farmer1->milkrate * $farmer1->milkamount),2);

        $farmer1->tc=0;
        $farmer1->cc=0;


        if($farmer1->farmer()->usetc==1 && $farmer1->total>0 ){
            $farmer1->tc= truncate_decimals(( ($center->tc *($snfAvg+$fatAvg)/100)* $farmer1->milkamount),2);
        }
        if($farmer1->farmer()->usecc==1 && $farmer1->total>0 ){
            $farmer1->cc=truncate_decimals( $center->cc * $farmer1->milkamount,2);
        }




        $farmer1->grandtotal=(int)( $farmer1->total+$farmer1->tc+$farmer1->cc);

        $farmer1->bonus=0;
        if (env('hasextra',0)==1){
            $farmer1->bonus=(int)($farmer1->grandtotal * $center->bonus/100);
        }


        $farmer1->due=(float)(Sellitem::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->sum('due'));

        $previousMonth=Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->where('identifire','101')->sum('amount');
        $previousMonth1=Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->where('identifire','120')->where('type',1)->sum('amount');
        $previousBalance=Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->where('identifire','120')->where('type',2)->sum('amount');

        $farmer1->advance=(float)(Advance::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->sum('amount'));
        $farmer1->prevdue=(float)$previousMonth+(float)$previousMonth1;
        $farmer1->prevbalance=(float)$previousBalance;
        $farmer1->paidamount=(float)Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->where('identifire','121')->where('type',1)->sum('amount');
        $balance=$farmer1->grandtotal+$farmer1->balance - $farmer1->prevdue -$farmer1->advance-$farmer1->due-$farmer1->paidamount+$farmer1->prevbalance-$farmer1->bonus;

        $farmer1->balance=0;
        $farmer1->nettotal=0;
        if($balance<0){
            $farmer1->balance=(-1)* $balance ;
        }
        if($balance>0){
            $farmer1->nettotal= $balance;
        }

        $farmer1->ledger = Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->orderBy('id','asc')->get();

        // dd(compact('snfFats','milkData','data','center','farmer1'));
        return view('users.farmer.data',compact('snfFats','milkData','data','center','farmer1'));
    }
}
