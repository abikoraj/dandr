<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appsell;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use App\LedgerManage;

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
                $ledger->addLedger('Payment Receipt', 1, $appsell->total,$appsell->date, 502, $appsell->id);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Appsell added successfully',
                'data' => $appsell
            ]);

        }

    }

}
