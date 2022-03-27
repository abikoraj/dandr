<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\Distributer;
use App\Models\Sms;
use App\sms\Aakash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SMSController extends Controller
{
    public function distributerCredit(Request $request){
        $data=[];
        foreach ($request->ids as $id) {

            $dis=Distributer::where('user_id',$id)->first();
            $dis->lastsms=Carbon::now();
            $dis->save();
            $d=[];
            $d['amount']=$request->input('amount_'.$id);
            $d['phone']=$request->input('phone_'.$id);
            $d['name']=$request->input('name_'.$id);
            array_push($data,(object)$d);
        }
        // dd($data);
        // dispatch(new SendSms($data));
        // $smsSender->dispatch();
        $aakash=new Aakash();
        foreach ($data as $item) {
            $msg=view('sms.distributer_credit',['dis'=>$item])->render();
            $aakash->sendMessage($item->phone,$msg);
        }
        return redirect()->back()->with('msg','SMS Sent Sucessfully');
    }

    public function customerCredit(Request $request){
        $customers=DB::select('select name,phone,(dr-cr) as due from
        (select
        name,
        phone,
        (select sum(amount) as cr from ledgers where ledgers.user_id=u.id and type=1) as cr,
        (select sum(amount) as dr from ledgers where ledgers.user_id=u.id and type=2) as dr
        from users u
        where u.id in '."(".implode(",", $request->ids).")".' ) as data
        ');
        $data=[];
        foreach ($customers as $key => $customer) {
            array_push($data,[
                'to'=>env('smstest',false)?"9844404665":$customer->phone,
                'msg'=>"Dear ".$customer->name.PHP_EOL.'Your Due is Rs. '. $customer->due.PHP_EOL.env('companyName')
            ]);
        }

        SMS::insert($data);
        $now=Carbon::now();
        DB::table('customers')->whereIn('user_id',$request->ids)->update([
            'lastsms'=>$now
        ]);
        return response('ok');
    }
}
