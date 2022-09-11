<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DayReportController extends Controller
{
    public function milk(Request $request)
    {
        if($request->getMethod()=="POST"){
            $date=str_replace('-','',$request->date);
            $milkDatas=DB::table('milkdatas')->select(DB::raw('
                sum(m_amount+e_amount) as amount,user_id
            '))->groupBy('user_id')->where('date',$date)->get();
            $snffats=DB::table('snffats')->select(DB::raw('
            avg(snf) as snf,avg(fat) as fat,user_id
            '))->groupBy('user_id')->where('date',$date)->get();
            
            $farmers=DB::table('farmers')->join('users','users.id','=','farmers.user_id')->select(DB::raw('
                users.name,users.no,farmers.*
            '))->get();
            $centers=DB::table('centers')->get();
        
            foreach ($farmers as $key => $farmer) {
                $farmer->tc = 0;
                $farmer->cc = 0;
                $farmer->milk = 0;
                $farmer->protsahan_rate = 0;
                $farmer->transport_rate=0;

                $snffat=$snffats->where('user_id',$farmer->user_id)->first();
                $nosnffat=false;
                if($snffat==null){
                    $snffat=DB::table('snffats')->where('date','<=',$date)->where('user_id',$farmer->user_id)->orderByDesc('date')->first();
                    $nosnffat=$snffat==null;
                }
                
                $center=$centers->where('id',$farmer->center_id)->first();
                if(!$nosnffat){

                    $farmer->fat = truncate_decimals($snffat->fat);
                    $farmer->snf = truncate_decimals($snffat->snf);
    
                    $fatAmount = ($farmer->fat * $center->fat_rate);
                    $snfAmount = ($farmer->snf * $center->snf_rate);
                }else{
                    $farmer->fat = 0;
                    $farmer->snf = 0;
                }

                $farmerMilkData=$milkDatas->where('user_id',$farmer->user_id)->first();
                if($farmerMilkData!=null){
                    $farmer->milk=$farmerMilkData->amount;
                }

                if ($farmer->userate == 1) {
                        
                    $farmer->rate = $farmer->rate;
                } else {
                    if($nosnffat){
                        $farmer->rate =0;

                    }else{

                        $farmer->rate = truncate_decimals($fatAmount + $snfAmount);
                    }
                }

                $farmer->total = truncate_decimals(($farmer->rate * $farmer->milk), 2);

                if ($farmer->usetc == 1 ) {
                    $farmer->tc = truncate_decimals((($center->tc * ($farmer->snf + $farmer->fat) / 100) ), 2);
                }
                if ($farmer->use_ts_amount == 1 ) {
                    $farmer->tc = truncate_decimals((($farmer->ts_amount) ), 2);
                }
                if ($farmer->usecc == 1 ) {
                    $farmer->cc = truncate_decimals($center->cc , 2);
                }
                if ($farmer->use_protsahan == 1 ) {
                    $farmer->protsahan_rate= truncate_decimals($farmer->protsahan , 2);
                }
                if ($farmer->use_transport == 1 ) {
                    $farmer->transport_rate= truncate_decimals($farmer->transport , 2);
                }
                $farmer->totalRate=$farmer->rate+$farmer->tc+ $farmer->cc +$farmer->protsahan_rate+$farmer->transport_rate;
                $farmer->grandtotal=truncate_decimals( $farmer->totalRate*$farmer->milk,2);
                
            }

        
            $farmers=$farmers->where('milk','>',0)->where('rate','>',0);
            $summary=(object)[
                "snf"=>truncate_decimals( $farmers->avg('snf'),2),
                "fat"=>truncate_decimals( $farmers->avg('fat'),2),
                "milk"=>$farmers->sum('milk'),
                "grandtotal"=>$farmers->sum('grandtotal'),
            ];
            // dd($farmers->groupBy('center_id'));
            $datas=$farmers->groupBy('center_id');

            return view('admin.report.day.milk.data',compact('datas','centers','date','summary'));
        }else{
            return view('admin.report.day.milk.index');
        }
    }

    public function index()
    {
        return view('admin.report.day.index');
    }
}
