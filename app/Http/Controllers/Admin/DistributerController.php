<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Distributer;
use App\Models\Distributerreq;
use App\Models\Distributorsell;
use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributerController extends Controller
{

    
    public function index(){
        return view('admin.distributer.index');
    }

    public function add(Request $request){
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $dis = new Distributer();
        $dis->user_id = $user->id;
        $dis->rate = $request->rate??0;
        $dis->amount = $request->amount??0;
        $dis->save();
        return view('admin.distributer.single',compact('user'));
    }

    public function list(){
        $distributer = User::join('distributers','distributers.user_id','=','users.id')->select('users.*',DB::raw('distributers.id as dis_id'))->where('users.role',2)->orderBy('distributers.id','asc')->get();
        // dd($distributer);
        return view('admin.distributer.list',compact('distributer'));
    }

    public function update(Request $request){
        $user = User::where('id',$request->id)->where('role',2)->first();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $dis = Distributer::where('user_id',$user->id)->first();
        $dis->rate = $request->rate??0;
        $dis->amount = $request->amount??0;
        $dis->save();
        return view('admin.distributer.single',compact('user'));

    }

    public function delete(Request $request){
        $user = User::where('id',$request->id)->first();
        $user->delete();
    }


    //XXX distributer Detail
    public function distributerDetail($id){
        $user = User::where('id',$id)->where('role',2)->first();
        return view('admin.distributer.detail',compact('user'));
    }

    public function distributerDetailLoad(Request $request){
        // $range=NepaliDate::getDate($r->year,$r->month,$r->session);
        // $ledger = Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->orderBy('ledgers.id','asc')->get();
        // $d = Distributer::where('user_id',$r->user_id)->first();
        // $sell = Distributorsell::where('distributer_id',$d->id)->where('date','>=',$range[1])->where('date','<=',$range[2])->get();
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

        return view('admin.distributer.data',compact('ledgers','type','user','title'));

    }

  

    public function opening(){
        return view('admin.distributer.balance.index');
    }

    public function loadLedger(Request $request){
        $date = str_replace('-','',$request->date);
        $ledgers=Ledger::where('date',$date)->where('identifire','119')->get();
        return view('admin.distributer.balance.list',compact('ledgers'));

    }
    public function ledger(Request $request){
        $date = str_replace('-','',$request->date);
        $dis=Distributer::where('id',$request->id)->first();
        $ledger=new LedgerManage($dis->user_id);
        $d=$ledger->addLedger("Opening Balance",$request->type,$request->amount,$date,'119');
        return view('admin.distributer.balance.single',compact('d'));

    }

    public function updateLedger(Request $request){
        // return response("multiple Ledger Already added Cannot be Deleted for distributor",500);

        $oldledger=Ledger::find($request->id);
        $user=User::find($oldledger->user_id);
        if(Ledger::where('user_id',$oldledger->user_id)->count()>1){
            return response("multiple Ledger Already added Cannot be Deleted",500);
        }
        $oldledger->delete();
        $user->amount=0;
        $user->save();
    }


    // distributer request
    public function distributerRequest(){
        $disReqs = Distributerreq::latest()->get();
        return view('admin.distributer.request',compact('disReqs'));
    }

    public function distributerRequestChangeStatus($id){
        $disReqs = Distributerreq::where('id',$id)->first();
        $disReqs->status = 1;
        $disReqs->save();
        return redirect()->back();
    }



}
