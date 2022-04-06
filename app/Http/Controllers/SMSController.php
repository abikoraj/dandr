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


    public function promo(Request $request){
        $data=[];
        if(env('smstest',false)){
            foreach ($request->phones as $key => $phone) {
                array_push($data,[
                    'to'=>"9800916365,9852078274,9844404665,9807014745",
                    'msg'=>"this is chhatra, if sms count <".count($request->phones )." contact me"
                ]);
            }
        }else{

            foreach ($request->phones as $key => $phone) {
                array_push($data,[
                    'to'=>$phone,
                    'msg'=>$request->msg
                ]);
            }
        }
        SMS::insert($data);
        return response('ok');
    }
    public function distributerCredit(Request $request){
        $data=[];
        foreach ($request->ids as $id) {
            $d=[];
            $due=$request->input('amount_'.$id);
            $phone=$request->input('phone_'.$id);
            $name=$request->input('name_'.$id);
            $msg=view('sms.distributer_credit',compact('due','name'))->render();
            array_push($data,[
                'to'=>env('smstest',false)?"9844404665":$phone,
                'msg'=>$msg
            ]);
        }
        SMS::insert($data);
        $now=Carbon::now();
        DB::table('distributers')->whereIn('user_id',$request->ids)->update([
            'lastsms'=>$now
        ]);
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
