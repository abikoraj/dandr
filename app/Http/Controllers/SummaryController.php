<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\NepaliDate;
use App\NepaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
        
            $date=(int)str_replace('-','',$request->date);
            $milkData=[];
            foreach (Center::all(['id','name']) as $key => $center) {
                array_push($milkData,(object)[
                    'center'=>$center->name,
                    'amounts'=> DB::table('milkdatas')->where('center_id',$center->id)->where('date',$date)->select(DB::raw('IFNULL(sum(e_amount),0) as e_amount ,IFNULL(sum(m_amount),0)  as m_amount'))->first()??((object)['m_amount'=>0,'e_amount'=>0])
                ]);
            }
            // dd($date,$milkData);
    
            $salesData=[];
            $salesData['farmer']= DB::selectOne("select sum(total) as total,sum(paid) as paid,sum(due) as due from sellitems where user_id in (select u.id from users u join farmers d on u.id=d.user_id) and date=${date}");
            $salesData['distributers']= DB::selectOne("select sum(total) as total,sum(paid) as paid,sum(due) as due from sellitems where user_id in (select u.id from users u join distributers d on u.id=d.user_id) and date=${date}");
            $counter1=DB::selectOne("select sum(grandtotal) as total,sum(paid) as paid,sum(due) as due from bills where date={$date}");
            $counter2=DB::selectOne("select sum(grandtotal) as total,sum(paid) as paid,sum(due) as due from pos_bills where date={$date}");
    
            $salesData['counter']=(object)[
                'total'=>$counter1->total+$counter2->total,
                'paid'=>$counter1->paid+$counter2->paid,
                'due'=>$counter1->due+$counter2->due,
            ];

            return view('admin.summary.data',compact('milkData','salesData'));

        }else{
            return view('admin.summary.index');
        }
    }
}
