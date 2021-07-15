<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Distributer;
use App\Models\Distributerreq;
use App\Models\Distributorsell;
use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DistributorDashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $distributor = Distributer::where('user_id',$user->id)->first();
        $purchase = Distributorsell::where('distributer_id',$distributor->id)->sum('total');
        $pay = Distributorsell::where('distributer_id',$distributor->id)->sum('paid');
        // dd($pay);
        $due = User::where('id',Auth::user()->id)->where('amounttype',1)->sum('amount');
        return view('users.distributor.index',compact('due','pay','purchase'));
    }


    public function transactionDetail(){
        return view('users.distributor.transaction');
    }

    public function loaddata(Request $request){
        $year=$request->year;
        $month=$request->month;
        $week=$request->week;
        $session=$request->session;
        $type=$request->type;
        $range=[];
        $data=[];
        $date=1;
        $title="";
        $ledger=Ledger::where('user_id',$request->user_id);
        if($type==0){
            $range = NepaliDate::getDate($request->year,$request->month,$request->session);
            $ledger=$ledger->where('date','>=',$range[1])->where('date','<=',$range[2]);
            $title="<span class='mx-2'>Year:".$year ."</span>";
            $title.="<span class='mx-2'>Month:".$month ."</span>";
            $title.="<span class='mx-2'>Session:".$session ."</span>";

        }elseif($type==1){
            $date=$date = str_replace('-','',$request->date1);
           $ledger=$ledger->where('date','=',$date);
           $title="<span class='mx-2'>Date:"._nepalidate($date) ."</span>";

        }elseif($type==2){
            $range=NepaliDate::getDateWeek($request->year,$request->month,$request->week);
            $ledger=$ledger->where('date','>=',$range[1])->where('date','<=',$range[2]);
            $title="<span class='mx-2'>Year:".$year ."</span>";
            $title.="<span class='mx-2'>Month:".$month ."</span>";
            $title.="<span class='mx-2'>Week:".$week ."</span>";

        }elseif($type==3){
            $range=NepaliDate::getDateMonth($request->year,$request->month);
           $ledger=$ledger->where('date','>=',$range[1])->where('date','<=',$range[2]);
           $title="<span class='mx-2'>Year:".$year ."</span>";
            $title.="<span class='mx-2'>Month:".$month ."</span>";

        }elseif($type==4){
            $range=NepaliDate::getDateYear($request->year);
            $ledger=$ledger->where('date','>=',$range[1])->where('date','<=',$range[2]);
            $title="<span class='mx-2'>Year:".$year ."</span>";


        }elseif($type==5){
            $range[1]=str_replace('-','',$request->date1);;
            $range[2]=str_replace('-','',$request->date2);;
             $ledger=$ledger->where('date','>=',$range[1])->where('date','<=',$range[2]);
             $title="<span class='mx-2'>from:".$request->date1 ."</span>";
            $title.="<span class='mx-2'>To:".$request->date2 ."</span>";

        }
        // dd($ledger->toSql(),$ledger->getBindings());
        $ledgers=$ledger->orderBy('id','asc')->get();
        $user=User::where('id',$request->user_id)->first();

        return view('users.distributor.data',compact('ledgers','type','user','title'));
    }

    public function changePasswordPage(){
        return view('users.distributor.changepass');
    }

    public function changePassword(Request $request){
        $request->validate([
            'n_pass' =>'required|min:8'
            ],
            [
            'n_pass.min' => 'Password should be at least 8 characters !'
        ]);
        $user = User::where('id',Auth::user()->id)->where('role',2)->first();
       if(Hash::check($request->c_pass, $user->password)){
          $user->password = bcrypt($request->n_pass);
          $user->save();
          return redirect()->back()->with('message','Password changed successfully !');
       }else{
        return redirect()->back()->with('message_danger','Current password does not matched !');
       }
    }


    // make a request
    public function makeArequest(){
        return view('users.distributor.request.index');
    }

    public function makeArequestAdd(Request $request){
        $date = str_replace('-','',$request->date);
        $req = new Distributerreq();
        $req->date = $date;
        $req->amount = $request->amount;
        $req->item_name = $request->item;
        $req->user_id = Auth::user()->id;
        $req->save();
        return redirect()->back()->with('message','Your request addedd successfully !');
    }

    public function makeArequestUpdate(Request $re){
        // dd($re->all());
        $date = str_replace('-','',$re->date);
        // dd($date);
        $req = Distributerreq::where('id',$re->id)->first();
        $req->date = $date;
        $req->amount = $re->amount;
        $req->item_name = $re->item;
        $req->save();
        return redirect()->back()->with('message','Your request updated successfully !');
    }

    public function requestDelete($id){
        $req = Distributerreq::where('id',$id)->first();
        $req->delete();
        return redirect()->back()->with('message','Your request deleted successfully !');
    }
}
