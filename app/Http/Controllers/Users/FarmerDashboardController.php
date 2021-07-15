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

    public function loadData(Request $r){
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
