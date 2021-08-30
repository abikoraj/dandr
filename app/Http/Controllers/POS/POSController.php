<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\CounterStatus;
use App\Models\Customer;
use App\Models\Item;
use App\Models\PosSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index(){
        $setting=PosSetting::first();
        if($setting==null){
            return redirect()->route('pos.day');
        }else{
            if(!$setting->open){
                return redirect()->route('pos.day');

            }
        }
        $id=session('counter');
        $xid=session('xid');
        // dd(session('counter'),session('xid'));
        $r=false;
        if($xid==null){
            $r=true;
        }else{

            if($id==null){
                $r=true;
            }else{
                $counter=Counter::find($id);
                if($counter->last==null){
                    
                }else{
                    $now=Carbon::now();
                    $diff=$counter->last->diffInSeconds($now);
                    
                    if($counter->sid!=$xid){
                        if($diff<=30){
                            $r=true;
                        }
                    }
                }
            }
        }
        if($r){
            return redirect()->route('pos.counter');
        }
        $counter=Counter::find($id);
        $status=$counter->currentStatus();
        if($status==null){
            return redirect()->route('pos.counter.open');
        }else{
            if($status->status==1){
                return redirect()->route('pos.counter.open');
            }
        }
        // if($status=)
        // dd($counter->currentStatus());
        return view('pos.index',compact('counter'));
    }

    public function counterOpen(Request $request){
        $id=session('counter');
        $xid=session('xid');
        if($id==null || $xid==null){
            return redirect()->route('pos.counter');
        }
        $counter=Counter::find($id);
        $status=$counter->currentStatus();
        $setting=PosSetting::first();
        if($request->getMethod()=="POST"){
            if($status!=null){
                if($status->status>1){
                    return  redirect()->route('pos.index');
                }else{
                    return redirect()->back();
                }
            }
            $status=new CounterStatus();
            $status->counter_id=$id;
            $status->request=$request->amount;
            if($setting->direct){
                $status->opening=$request->amount;
                $status->current=$request->amount;
                $status->status=2;

            }else{
                $status->status=1;
            }
            $status->active=1;
            $status->date=$setting->date;
            $status->save();
           
            return redirect()->back();
        }else{
            if($status!=null){
                if($status->status>1){
                    return  redirect()->route('pos.index');
                }
            }
            return view('pos.counter.open',compact('setting','status'));
        }
    }
    public function counter(Request $request){
        // dd($request->all());
        if($request->getMethod()=="POST"){
            // dd($request->all(),session('counter'));
            $counter=Counter::find($request->id);
            if($counter->last==null){
                // return redirect()->back();
            }else{
                $now=Carbon::now();
                $diff=$counter->last->diffInSeconds($now);
                if($diff<30){
                    return redirect()->back();
                }

            }
            $_xid=mt_rand(10000,99999);
            $counter->status=1;
            $counter->sid=$_xid;
            $counter->last=Carbon::now();
            $counter->save();
            session(['xid'=>$_xid]);
            session(['counter'=>$counter->id]);
            return redirect()->route('pos.index');
        }else{
        
            $setting=PosSetting::first();
            if($setting==null){
                return redirect()->route('pos.day');
            }else{
                if(!$setting->open){
                    return redirect()->route('pos.day');
    
                }
            }

            $counters=Counter::all();
            $data=[];
            foreach ($counters as $key => $counter) {
                if($counter->last==null){
                    array_push($data,$counter);
                }else{
                    $now=Carbon::now();
                    $diff=$counter->last->diffInSeconds($now);
                    // dd($diff);
                    if($diff>30){
                        array_push($data,$counter);
                    }
                }
            }
            return view('pos.counter.index',compact('data'));
        }
    }
    public function counterStatus(Request $request){
        $id=session('counter');
        $xid=session('xid');
        if($id==null){
            return response('counter expired',500);
        }
        $date=Carbon::now();
        Counter::where('id',$id)->update(['last' => $date]);
        dd($date);
    
    }

    public function items(){
        $items=Item::where('posonly',1)->select(
            DB::raw('id,title as name,number as barcode,sell_price as rate')
        )->get();
        // dd($items);
        return response()->json($items);
    }

    public function customers(){
        $items=Customer::join('users','users.id','=','customers.user_id')->select('customers.id','users.name','customers.user_id')->get();
        // dd($items);
        return response()->json($items);
    }

    public function searchCustomer(Request $request){
        $items=Customer::join('users','users.id','=','customers.user_id')->select('customers.id','users.name','users.address','customers.user_id','users.phone');
        if($request->filled('name')){
            $items=$items->where('users.name','like',$request->name."%");
        }

        if($request->filled('phone')){
            $items=$items->where('users.phone',$request->phone);
        }
        // dd($items);
        $allItems=$items->get();
        return response()->json($allItems);
    }

    public function customersAdd(Request $request) {
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->amount = $request->amount??0;
        $user->amounttype = $request->amounttype??0;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $customer = new Customer();
        $customer->user_id=$user->id;
        $customer->save();
        $customer->user=$user;
        $data=Customer::join('users','users.id','=','customers.user_id')->select('customers.id','users.name','users.address','customers.user_id','users.phone')->where('customers.id',$customer->id)->first();
        return response()->json($data);
       
    }

}
