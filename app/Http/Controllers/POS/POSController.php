<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Counter;
use App\Models\CounterStatus;
use App\Models\Customer;
use App\Models\Item;
use App\Models\PaymentGateway;
use App\Models\PosSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class POSController extends Controller
{
    public function index()
    {
        $setting = PosSetting::first();
        if ($setting == null) {
            return redirect()->route('pos.day');
        } else {
            if (!$setting->open) {
                return redirect()->route('pos.day');
            }
        }
        $id = session('counter');
        $xid = session('xid');
        // dd(session('counter'),session('xid'));
        $r = false;
        if ($xid == null) {
            $r = true;
        } else {

            if ($id == null) {
                $r = true;
            } else {
                $counter = Counter::find($id);
                if ($counter->last == null) {
                } else {
                    $now = Carbon::now();
                    $diff = $counter->last->diffInSeconds($now);

                    if ($counter->sid != $xid) {
                        if ($diff <= 30) {
                            $r = true;
                        }
                    }
                }
            }
        }
        if ($r) {
            return redirect()->route('pos.counter');
        }
        $counter = Counter::find($id);
        $status = $counter->currentStatus();
        if ($status == null) {
            return redirect()->route('pos.counter.open');
        } else {
            if ($status->status == 1) {
                return redirect()->route('pos.counter.open');
            } else if ($status->status == 3) {
                return redirect()->route('pos.counter');
            }
        }
        // if($status=)
        // dd($counter->currentStatus());
        $banks = Bank::all();
        $gateways = PaymentGateway::all();
        return view('pos.index', compact('counter', 'banks', 'gateways'));
    }

    public function counterOpen(Request $request)
    {
        $id = session('counter');
        $xid = session('xid');
        if ($id == null || $xid == null) {
            return redirect()->route('pos.counter');
        }
        $counter = Counter::find($id);
        $status = $counter->currentStatus();
        $setting = PosSetting::first();
        if ($request->getMethod() == "POST") {
            if ($status != null) {
                if ($status->status > 1) {
                    return  redirect()->route('pos.index');
                } else {
                    return redirect()->back();
                }
            }
            $status = new CounterStatus();
            $status->counter_id = $id;
            $status->request = $request->amount;
            if ($setting->direct) {
                $status->opening = $request->amount;
                $status->current = $request->amount;
                $status->status = 2;
            } else {
                $status->status = 1;
            }
            $status->active = 1;
            $status->date = $setting->date;
            $status->save();

            return redirect()->back();
        } else {
            if ($status != null) {
                if ($status->status > 1) {
                    return  redirect()->route('pos.index');
                }
            }
            return view('pos.counter.open', compact('setting', 'status'));
        }
    }
    public function counter(Request $request)
    {
        // dd($request->all());
        $setting = PosSetting::first();
        if ($setting == null) {
            return redirect()->route('pos.day');
        } else {
            if (!$setting->open) {
                return redirect()->route('pos.day');
            }
        }
        if ($request->getMethod() == "POST") {
            // dd($request->all(),session('counter'));
            $counter = Counter::find($request->id);
            if ($counter->last == null) {
                // return redirect()->back();
            } else {
                $now = Carbon::now();
                $diff = $counter->last->diffInSeconds($now);
                if ($diff < 30) {
                    return redirect()->back();
                }
            }
            $_xid = mt_rand(10000, 99999);
            $counter->status = 1;
            $counter->sid = $_xid;
            $counter->last = Carbon::now();
            $counter->save();
            if(!env('use_opening',false)){
                $status=$counter->currentStatus();
                if($status==null){
                  $status=new CounterStatus();
                  $status->counter_id=$counter->id;
                  $status->date=$setting->date;
                }
                $status->status=2;
                $status->save();
            }
            session(['xid' => $_xid]);
            session(['counter' => $counter->id]);
            return redirect()->route('pos.index');
        } else {



            $counters = Counter::all();
            $data = [];
            foreach ($counters as $key => $counter) {
                $status = $counter->currentStatus();
                $ok = false;
                if ($status == null) {
                    $ok = true;
                } else {
                    if ($status->status < 3) {
                        $ok = true;
                    }
                }
                if ($ok) {
                    if ($counter->last == null) {
                        array_push($data, $counter);
                    } else {
                        $now = Carbon::now();
                        // dd($now);
                        $diff = $counter->last->diffInSeconds($now);
                        // dd($diff);
                        if ($diff > 300) {
                            array_push($data, $counter);
                        }
                    }
                }
            }
            return view('pos.counter.index', compact('data'));
        }
    }

    public function counterStatus(Request $request)
    {
        $id = session('counter');
        $xid = session('xid');
        if ($id == null) {
            return response('counter expired', 500);
        }
        $date = Carbon::now();
        Counter::where('id', $id)->update(['last' => $date]);
        dd($date);
    }

    public function counterAnother(Request $request)
    {
        $request->session()->forget(['counter', 'xid']);
        return redirect()->route('pos.index');
    }

    public function counterCurrent(Request $request)
    {
        $id = session('counter');
        $xid = session('xid');
        if ($id == null || $xid == null) {
            return response('counter not found', 404);
        }
        $counter = Counter::find($id);
        $status = $counter->currentStatus();
        return response()->json($status);
    }

    public function counterClose(Request $request)
    {
        $id = session('counter');
        $xid = session('xid');
        if ($id == null || $xid == null) {
            return response('counter not found', 404);
        }
        $counter = Counter::find($id);
        $status = $counter->currentStatus();
        if ($status->current > $request->closing) {
            if (!$request->filled('ok')) {
                return response('The Closing Amount is less than currentbalance.', 500);
            }
        }
        $status->closing = $request->closing;
        $status->status = 3;
        $status->save();
        $request->session()->forget('counter');
        return response()->json(['sucess' => true]);
    }

    public function items()
    {
        $items = Item::where('posonly', 1)->select(
            DB::raw('id,title as name,number as barcode,sell_price as rate,wholesale,tax,taxable')
        )->get();
        // dd($items);
        return response()->json($items);
    }

    public function itemSingle(Request $request){
        if($request->filled('barcode')){
            return response()->json(Item::where('number',$request->barcode)->select(
                DB::raw('id,title as name,number as barcode,sell_price as rate,tax,taxable,wholesale')
            )->first());
        }
    }

    public function customers()
    {
        $items = Customer::join('users', 'users.id', '=', 'customers.user_id')->select('customers.id', 'users.name', 'customers.user_id')->get();
        // dd($items);
        return response()->json($items);
    }

    public function searchCustomer(Request $request)
    {
        $items = Customer::join('users', 'users.id', '=', 'customers.user_id')->select('customers.id', 'users.name', 'users.address', 'customers.user_id', 'users.phone', 'customers.panvat',);
        if ($request->filled('name')) {
            $items = $items->where('users.name', 'like', $request->name . "%");
        }

        if ($request->filled('phone')) {
            $items = $items->where('users.phone', 'like', $request->phone . "%");
        }
        // dd($items);
        $allItems = $items->get();
        return response()->json($allItems);
    }

    public function customersAdd(Request $request)
    {
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->amount = $request->amount ?? 0;
        $user->amounttype = $request->amounttype ?? 0;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->save();
        $customer->user = $user;
        $data = Customer::join('users', 'users.id', '=', 'customers.user_id')->select('customers.id', 'users.name', 'users.address', 'customers.user_id', 'users.phone')->where('customers.id', $customer->id)->first();
        return response()->json($data);
    }
}
