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
                users.name,users.id,users.no,farmers.center_id
            '))->get();
            $centers=DB::table('centers')->get();
        
            foreach ($farmers as $key => $farmer) {
                $farmer->tc = 0;
                $farmer->cc = 0;
                $farmer->protsahan_amount = 0;
                $farmer->transport_amount=0;
                $snffat=$snffats->where('user_id',$farmer->id)->first();
                
                $center=$centers->where('id',$farmer->center_id)->first();

                $farmer->fat = truncate_decimals($snffat->fat);
                $farmer->snf = truncate_decimals($snffat->snf);

                $fatAmount = ($farmer->fat * $center->fat_rate);
                $snfAmount = ($farmer->snf * $center->snf_rate);

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
            }
            dd($farmer);
        }else{
            return view('admin.report.day.milk.index');
        }
    }
}
