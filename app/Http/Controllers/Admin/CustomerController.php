<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Customer;
use App\Models\CustomerPayment;
use App\Models\Ledger;
use App\Models\PosSetting;
use App\Models\User;
use App\NepaliDate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $large = env('large', false);
        $customers = [];
        if ($request->getMethod() == "POST") {
            // $query1 = Customer::join('users', 'users.id', '=', 'customers.user_id')
            //     ->select('users.name', 'users.address', 'users.phone', 'customers.id', 'customers.user_id', 'customers.panvat');
            // $query = Customer::join('users', 'users.id', '=', 'customers.user_id')
            //     ->select('users.name', 'users.address', 'users.phone', 'customers.id', 'customers.user_id', 'customers.panvat');
            // $step = $request->step ?? 0;
            // $countStep = env('countstep', 24);
            // $data = [];
            // $data['page'] = $step;
            // if ($request->filled('name')) {
            //     $query = $query->where('users.name', 'like', '%' . $request->name . '%');
            //     $query1 = $query1->where('users.name', 'like', '%' . $request->name . '%');
            //     $data['name'] = $request->name;
            // }

            // if ($request->filled('phone')) {
            //     $query = $query->where('users.phone', 'like', $request->phone . '%');
            //     $query1 = $query1->where('users.phone', 'like', $request->phone . '%');
            //     $data['phone'] = $request->phone;
            // }
            // // $temp=$query;
            // if ($step == 0) {
            //     $query = $query->take($countStep);
            // } else {
            //     $query = $query->skip($step * $countStep)->take($countStep);
            // }
            // $items = $query->orderBy('users.name', 'asc')->get();
            // $data['total'] = $query1->count();
            // $data['items'] = $items;
            $data = DB::table('customers')
                ->join('users', 'users.id', '=', 'customers.user_id')
                ->select(
                    'users.name',
                    'users.address',
                    'users.phone',
                    'customers.id',
                    'customers.user_id',
                    'customers.points'
                )->get();
            return response()->json($data);
        } else {

            return view('admin.customer.index');
        }
    }

    public function all()
    {
        $customers = Customer::join('users', 'users.id', '=', 'customers.user_id')
            ->select('users.name', 'customers.id', 'customers.user_id','users.phone')->orderBy('users.name', 'asc')->get();
        return response()->json($customers);
    }

    public function add(Request $request)
    {
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        // $user->amount = $request->amount??0;
        // $user->amounttype = $request->amounttype??0;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->panvat = $request->panvat;
        $customer->save();

        $customer->name = $user->name;
        $customer->address = $user->address;
        $customer->phone = $user->phone;
        // $customer->user=$user;
        if ($request->filled('amount')) {
            if ($request->amount > 0) {

                $ledger = new LedgerManage($user->id);
                $date = PosSetting::getdate();
                $ledger->addLedger('Opening Balance', $request->amounttype, $request->amount, $date, 134);
            }
        }
        if ($request->filled('json')) {
            return response()->json($customer);
        } else {
            return view('admin.customer.single', compact('customer'));
        }
    }

    public function update(Request $request)
    {
        $customer = Customer::where('id', $request->id)->first();
        $user = User::where('id', $customer->user_id)->first();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->save();
        $customer->panvat = $request->panvat;
        $customer->save();
        $customer->name = $user->name;
        $customer->address = $user->address;
        $customer->phone = $user->phone;
        if ($request->filled('json')) {
            return response()->json($customer);
        } else {
            return view('admin.customer.single', compact('customer'));
        }
    }

    public function detail($id, Request $request)
    {
        $customer=DB::table('customers')->where('id',$id)->first();
        $user = DB::table('users')->where('id', $customer->user_id)->first();

        if ($request->getMethod() == "POST") {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];
            $date = 1;
            $title = "";

            $ledger = Ledger::where('user_id', $user->id);
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $ledger = $ledger->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
                $title .= "<span class='mx-2'>Session:" . $session . "</span>";
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $ledger = $ledger->where('date', '=', $date);
                $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $ledger = $ledger->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
                $title .= "<span class='mx-2'>Week:" . $week . "</span>";
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $ledger = $ledger->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $ledger = $ledger->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $ledger = $ledger->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>from:" . $request->date1 . "</span>";
                $title .= "<span class='mx-2'>To:" . $request->date2 . "</span>";
            }
            // dd($ledger->toSql(),$ledger->getBindings());
            $prev = 0;
            if ($type == 1) {
                $prev = Ledger::where('date', '<', $date)->where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('date', '<', $date)->where('user_id', $user->id)->where('type', 1)->sum('amount');
            } else if ($type = -1) {
                $prev = 0;
            } else {
                $prev = Ledger::where('date', '<', $range[1])->where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('date', '<', $range[1])->where('user_id', $user->id)->where('type', 1)->sum('amount');
            }
            $base = $prev;
            $ledger_data = $ledger->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
            $ledgers = [];
            foreach ($ledger_data as $ledger) {
                if ($ledger->type == 1) {
                    $base -= $ledger->amount;
                } else {
                    $base += $ledger->amount;
                }
                $ledger->amt = $base;
                array_push($ledgers, $ledger);
            }

            return view('admin.customer.load_detail', compact('ledgers','customer', 'type', 'user', 'title', 'prev'));
        } else {
            return view('admin.customer.detail', compact('user','customer'));
        }
    }

    public function payment(Request $request)
    {
        $large = env('large', false);
        $customers = [];
        if ($request->getMethod() == "POST") {
            $user = User::find($request->id);
            $payments=DB::table('ledgers')->where('user_id', $user->id)->where('identifire',135)->get(['id','foreign_key','date','amount']);
            $balance = Ledger::where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('user_id', $user->id)->where('type', 1)->sum('amount');
            return view('admin.customer.payement.data', compact('user', 'balance','payments'));
        } else {

            return view('admin.customer.payement.index' );
        }
    }

    public function addPayment(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $user = User::find($request->id);
        $payment = new CustomerPayment();
        $payment->amount = $request->amount;
        $payment->description = $request->description;
        $payment->date = $date;
        $payment->user_id = $request->id;
        $payment->save();

        $ledger = new LedgerManage($user->id);
        if (env('acc_system', 'old') == 'old') {
            $ledger->addLedger("Payment", 2, $payment->amount, $date, 135, $payment->id);
        } else {
            $ledger->addLedger("Payment", 1, $payment->amount, $date, 135, $payment->id);
        }
        $user = User::find($request->id);
        return response('ok');
    }

    public function delPayment(Request $request)
    {

        DB::table('customer_payments')->where('id',$request->payment_id)->delete();
        DB::table('ledgers')->where('id',$request->id)->delete();
        return response('ok');

    }
    public function creditList(Request $request)
    {
        if($request->getMethod()=="POST"){
            $date = str_replace('-', '', $request->date);
            $now=Carbon::now();
            $customers=DB::select('select id,name,phone,address,(dr-cr) as due,latestPay,lastsms,last from
            (select
            u.id,
            name,
            phone,
            address,
            ifnull(c.lastsms,"N/A") as lastsms,
            ifnull(DATEDIFF(?,c.lastsms),"N/A") as last,
            (select sum(amount) as cr from ledgers where ledgers.user_id=u.id and type=1) as cr,
            (select sum(amount) as dr from ledgers where ledgers.user_id=u.id and type=2) as dr,
            ifnull((select max(date) from customer_payments where user_id = u.id),0) as latestPay
             from customers c join users u on u.id=c.user_id) as data where (data.dr - data.cr)>0
             and latestPay < ?
            ',[$now->format('Y-m-d'),$date]);
            return response()->json($customers);
        }else{

            return view('admin.customer.credit.index');
        }
    }

    public function promo(Request $request)
    {
        if($request->getMethod()=="POST"){
            return response()->json(
                DB::select('select u.name,u.id,u.phone,u.address from users u join customers c on u.id=c.user_id where c.center_id in ('. implode(",",$request->centers) .')')
            );
        }else{

            $centers=DB::table('centers')->get(['id','name']);
            return view('admin.customer.promo.index',compact('centers'));
        }
    }
}
