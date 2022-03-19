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
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $large = env('large', false);
        $customers = [];
        if ($request->getMethod() == "POST") {
            $query1 = Customer::join('users', 'users.id', '=', 'customers.user_id')
                ->select('users.name', 'users.address', 'users.phone', 'customers.id', 'customers.user_id', 'customers.panvat');
            $query = Customer::join('users', 'users.id', '=', 'customers.user_id')
                ->select('users.name', 'users.address', 'users.phone', 'customers.id', 'customers.user_id', 'customers.panvat');
            $step = $request->step ?? 0;
            $countStep = env('countstep', 24);
            $data = [];
            $data['page'] = $step;
            if ($request->filled('name')) {
                $query = $query->where('users.name', 'like', '%' . $request->name . '%');
                $query1 = $query1->where('users.name', 'like', '%' . $request->name . '%');
                $data['name'] = $request->name;
            }

            if ($request->filled('phone')) {
                $query = $query->where('users.phone', 'like', $request->phone . '%');
                $query1 = $query1->where('users.phone', 'like', $request->phone . '%');
                $data['phone'] = $request->phone;
            }
            // $temp=$query;
            if ($step == 0) {
                $query = $query->take($countStep);
            } else {
                $query = $query->skip($step * $countStep)->take($countStep);
            }
            $items = $query->orderBy('users.name', 'asc')->get();
            $data['total'] = $query1->count();
            $data['items'] = $items;

            return response()->json($data);
        } else {
            if (!$large) {

                $customers = Customer::join('users', 'users.id', '=', 'customers.user_id')
                    ->select('users.name', 'users.address', 'users.phone', 'customers.id', 'customers.user_id', 'customers.panvat')->get();
            }
            return view('admin.customer.index', compact('customers', 'large'));
        }
    }

    public function all(){
        $customers = Customer::join('users', 'users.id', '=', 'customers.user_id')
        ->select('users.name', 'customers.id', 'customers.user_id')->orderBy('users.name','asc')->get();
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
        $user = User::where('id', $id)->first();
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

            $ledger = Ledger::where('user_id', $request->user_id);
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
            $user = User::where('id', $request->user_id)->first();
            if ($type == 1) {
                $prev = Ledger::where('date', '<', $date)->where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('date', '<', $date)->where('user_id', $user->id)->where('type', 1)->sum('amount');
            } else if ($type = -1) {
                $prev = 0;
            } else {
                $prev = Ledger::where('date', '<', $range[1])->where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('date', '<', $range[1])->where('user_id', $user->id)->where('type', 1)->sum('amount');
            }
            $base=$prev;
            $ledger_data = $ledger->orderBy('date', 'asc')->orderBy('id','asc')->get();
            $ledgers=[];
            foreach($ledger_data as $ledger){
                if($ledger->type==1){
                    $base-=$ledger->amount;
                }else{
                    $base+=$ledger->amount;
                }
                $ledger->amt=$base;
                array_push($ledgers,$ledger);
            }

            return view('admin.customer.load_detail', compact('ledgers', 'type', 'user', 'title', 'prev'));
        } else {
            return view('admin.customer.detail', compact('user'));
        }
    }

    public function payment(Request $request)
    {
        $large=env('large',false);
        $customers=[];
        if ($request->getMethod() == "POST") {
            $user = User::find($request->id);
            $balance = Ledger::where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('user_id', $user->id)->where('type', 1)->sum('amount');
            return view('admin.customer.payement.data', compact('user','balance'));
        } else {
            if(!$large){
                $customers=Customer::with('user')->get();
            }
            return view('admin.customer.payement.index', compact(
                'customers',
                'large'
            ));
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
        return view('admin.customer.payement.data', compact('user'));
    }
}
