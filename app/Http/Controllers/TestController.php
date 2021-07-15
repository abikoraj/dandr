<?php

namespace App\Http\Controllers;

use App\Models\Distributer;
use App\Models\Farmer;
use App\Models\Ledger;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index($id){
        $farmers=Farmer::where('center_id',$id)->get();
        $datas=[];
        foreach($farmers as $farmer)
        {

            $user=$farmer->user;
            if($user!=null){
                $data=[];
                $data['name']=$user->name;
                $data['id']=$user->id;
                $data['no']=$user->no;
                $data['amount']=$user->amount;
                $data['type']=$user->amounttype;
                $ledgers=Ledger::where('user_id',$user->id)->orderBy('date', 'ASC') ->orderBy('id', 'ASC')->get();
                if(count($ledgers)>0){
                    $ledger=$user->ledgers->last();
                    $data['date']=_nepalidate($ledger->date);

                    $data['cr']=$ledger->cr;
                    $data['dr']=$ledger->dr;
                }else{
                    $data['date']="----------";
                    $data['cr']=0;
                    $data['dr']=0;

                }

                if($data['type']==1){
                    $data['ok']=$data['amount']==$data['cr'] || ($data['cr']==null && $data['amount']==0);
                }else{
                    $data['ok']=$data['amount']==$data['dr'] || ($data['dr']==null && $data['amount']==0);

                }
                array_push($datas,(object)$data);
            }
        }
        return view('testing.index',compact('datas'));
    }

    public function all($id){
        $user=User::find($id);
        $ledgers=Ledger::where('user_id',$id)->orderBy('date', 'ASC') ->orderBy('id', 'ASC')->get()->toArray();
        return view('testing.all',['user'=>$user->toArray(),'datas'=>$ledgers]);

        // dd($user->toArray(),$ledgers);
    }

    public function distributor(){
        $distributors=Distributer::all();
        $datas=[];
        foreach($distributors as $distributor){
            $distributor->user=User::find($distributor->user_id);
            $ledgers=[];
            $ls=Ledger::where('user_id',$distributor->user_id)->orderBy('id', 'ASC')->get();
            $distributor->wrong=false;
            $first=0;
            foreach($ls as $ledger){
                $amount=0;
                $track=0;
                if($ledger->cr>0){
                    $amount=(-1)*$ledger->cr;
                }elseif($ledger->dr>0){
                    $amount=$ledger->dr;
                }

                if($ledger->type==1){
                    $track=$amount+$ledger->amount;

                }else{
                    $track=$amount-$ledger->amount;

                }

                $ledger->wrong=$first!=$track;
                $ledger->first=$first;
                $ledger->track=$track;
                if($ledger->wrong){
                    $distributor->wrong=true;
                }
                $first=$amount;
                array_push($ledgers,$ledger);
            }
            $distributor->ledgers=$ledgers;
            array_push($datas,$distributor);
        }
        return view('testing.distributor',compact('datas'));
    }


    public function distributorByDate(){
        $distributors=Distributer::all();
        $datas=[];
        foreach($distributors as $distributor){
            $distributor->user=User::find($distributor->user_id);
            $ledgers=[];
            $ls=Ledger::where('user_id',$distributor->user_id)->orderBy('date', 'ASC')->orderBy('id', 'ASC')->get();
            $distributor->wrong=false;
            $first=0;
            foreach($ls as $ledger){
                $amount=0;
                $track=0;
                if($ledger->cr>0){
                    $amount=(-1)*$ledger->cr;
                }elseif($ledger->dr>0){
                    $amount=$ledger->dr;
                }

                if($ledger->type==1){
                    $track=$amount+$ledger->amount;

                }else{
                    $track=$amount-$ledger->amount;

                }

                $ledger->wrong=$first!=$track;
                $ledger->first=$first;
                $ledger->track=$track;
                if($ledger->wrong){
                    $distributor->wrong=true;
                }
                $first=$amount;
                array_push($ledgers,$ledger);
            }
            $distributor->ledgers=$ledgers;
            array_push($datas,$distributor);
        }
        return view('testing.distributor',compact('datas'));
    }
}
