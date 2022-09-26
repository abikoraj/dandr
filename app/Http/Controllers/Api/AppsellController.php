<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appsell;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\LedgerManage;
use App\Models\Appbuy;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class AppsellController extends Controller
{
    public function appsell(Request $request)
    {
        $date = getNepaliDate($request->date);
        $phone = $request->phone;
        if ($request->filled('phone')) {
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                $user = new User();
                $user->phone = $request->phone;
                $user->name = $request->name;
                $user->address = "Biratnagar";
                $user->role= 2;
                $user->amount = 0;
                $user->password= bcrypt('12345678');
                $user->save();
                $customer = new Customer();
                $customer->user_id = $user->id;
                $customer->center_id = 0;
                $customer->foreign_id = 0;
                $customer->save();
            }
            $appsell = new Appsell();
            $appsell->title = $request->title;
            $appsell->date = $date;
            $appsell->total = $request->total;
            $appsell->paid = $request->paid;
            $appsell->user_id = $user->id;
            $appsell->due = $request->due;
            $appsell->save();
            $ledger = new LedgerManage($appsell->user_id);
            $ledger->addLedger($appsell->title, 2, $appsell->total, $appsell->date, 501, $appsell->id);
            if ($appsell->paid > 0) {
                $ledger->addLedger('Payment Received', 1, $appsell->paid,$appsell->date, 502, $appsell->id);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Appsell added successfully',
                'data' => $appsell
            ]);

        }

    }

    public function customerPay(Request $request)
    {
        $date = getNepaliDate($request->date);
        $phone = $request->phone;
        if ($request->filled('phone')) {
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                $user = new User();
                $user->phone = $request->phone;
                $user->name = $request->name;
                $user->address = "Biratnagar";
                $user->role= 2;
                $user->amount = 0;
                $user->password= bcrypt('12345678');
                $user->save();
                $customer = new Customer();
                $customer->user_id = $user->id;
                $customer->center_id = 0;
                $customer->foreign_id = 0;
                $customer->save();
            }
            $ledger = new LedgerManage($user->id);
            if ($request->paid > 0) {
                $ledger->addLedger('Payment Receipt', 1, $request->paid, $date, 503);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Customer Payment Received Successfully',
                'data' => $request->all()
            ]);

        }

    }

    public function appBuy(Request $request)
    {
        $date = getNepaliDate($request->date);
        $phone = $request->phone;
        if ($request->filled('phone')) {
            $user = User::where('phone', $phone)->first();
            if (!$user) {
                $user = new User();
                $user->phone = $request->phone;
                $user->name = $request->name;
                $user->address = "Biratnagar";
                $user->role= 3;
                $user->amount = 0;
                $user->password= bcrypt('12345678');
                $user->save();
                $supplier = new Supplier();
                $supplier->user_id = $user->id;
                $supplier->save();
            }
            $appbuy = new Appbuy();
            $appbuy->title = $request->title;
            $appbuy->date = $date;
            $appbuy->total = $request->total;
            $appbuy->paid = $request->paid;
            $appbuy->user_id = $user->id;
            $appbuy->due = $request->due;
            $appbuy->save();
            $ledger = new LedgerManage($appbuy->user_id);
            $ledger->addLedger($appbuy->title, 1, $appbuy->total, $appbuy->date, 504, $appbuy->id);
            if ($appbuy->paid > 0) {
                $ledger->addLedger('Payment Made', 2, $appbuy->paid,$appbuy->date, 505, $appbuy->id);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Appbuy added successfully',
                'data' => $appbuy
            ]);

        }

    }

    public function dueList()
    {
        // $appsell = Appsell::where('due', '>', 0)->get();
        // $ledger = DB::table('ledgers')->where('type', 2)->where('amount', '>', 0)->whereIn('user_id',$appsell->pluck('user_id'))->get();

        $users=DB::select('select d.* from (select
         ((select sum(amount) from ledgers where user_id=u.id and type=2)-
         (select sum(amount) from ledgers where user_id=u.id and type=1)) as due,
         u.name,
         u.phone
         from users u join customers c on u.id=c.user_id) d where d.due>0');

        return response()->json([
            'status' => 'success',
            'message' => 'Due List',
            'users' => $users
        ]);
    }






}
