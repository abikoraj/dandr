<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customerController extends Controller
{
    public function index($center_id)
    {
        $customers = DB::select("select m.name,m.phone,m.address,c.panvat,c.foreign_id as id from customers c join member m on m.user_id=c.user_id where c.center_id=?", [$center_id]);
        return response()->json('customers');
    }

    public function ledger(Request $request)
    {
        $customer = Customer::where('foreign_id', $request->id)->first();
        if ($customer != null) {
            $ledgers = DB::table('ledgers')->where('user_id', $customer->user_id)->select('id', 'title', 'amount', 'date')->orderBy('date', 'asc')->orderBy('id')->get();
        } else {
            $ledgers = [];
        }
        return response(json_encode($ledgers, JSON_NUMERIC_CHECK));
    }

    public function unsyncedCustomers($center_id)
    {
        $customers = DB::select("select u.name,u.phone,u.address,c.panvat from customers c join users u on u.id=c.user_id where c.center_id=? and foreign_id=0", [$center_id]);
        return response()->json($customers);
    }

    public function fetchCustomer($center_id,Request $request)
    {
        $ids=$request->ids;
        $idString="(".implode(",",$ids).")";
        $customers = DB::select("select u.name,u.phone,u.address,c.panvat,c.foreign_id as id from customers c join users u on u.id=c.user_id where c.center_id=? and foreign_id in ".$idString, [$center_id]);
        return response()->json($customers);
    }

    public function pushCustomer(Request $request, $center_id)
    {
       
        $customers=Customer::where('center_id', $center_id)->get();
        foreach ($request->customers as $key => $cus) {
            # code...
            $customer = (object)$cus;
            $new_cus = $customers->where('foreign_id', $customer->id)->where('center_id', $center_id)->first();
            // dd($customer);
            if ($new_cus == null) {
                $cus_user = User::where('phone', $customer->phone)->first();
                if ($cus_user == null) {
                    $cus_user = new User();
                    $cus_user->password = bcrypt($customer->phone);
                    $cus_user->role = 5;
                }
                $cus_user->name = $customer->name;
                $cus_user->address = $customer->address ?? "";
                $cus_user->phone = $customer->phone;
                $cus_user->save();
    
                $new_cus = Customer::where('user_id', $cus_user->id)->first();
                if ($new_cus == null) {
                    $new_cus = new Customer();
                }
                $new_cus->panvat = $customer->panvat;
                $new_cus->center_id = $center_id;
                $new_cus->user_id = $cus_user->id;
                $new_cus->foreign_id = $customer->id;
                $new_cus->save();
            } else {
                $cus_user = User::where('id', $new_cus->user_id)->first();
                $cus_user->name = $customer->name;
                $cus_user->address = $customer->address ?? "";
                $cus_user->phone = $customer->phone;
                $cus_user->save();
    
    
                $new_cus->panvat = $customer->panvat;
                $new_cus->center_id = $request->center_id;
                $new_cus->user_id = $cus_user->id;
                $new_cus->foreign_id = $customer->id;
                $new_cus->save();
            }
        }
    }

    // public function syncRemote(){

    // }

}
