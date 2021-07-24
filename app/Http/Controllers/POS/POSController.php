<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index(){
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
        return view('pos.index',compact('counter'));
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
        Counter::where('id',$id)->update(['last' => Carbon::now()]);
    
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
        return response()->json($customer);
       
    }

}
