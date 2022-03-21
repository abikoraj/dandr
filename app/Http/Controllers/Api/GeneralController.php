<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller
{
    public function info()
    {
        return response()->json([
            'name'=>env('companyName'),
            'phone'=>env('companyphone'),
            'reg'=>env('companyRegNO'),
            'panvat'=>env('companyVATPAN'),
            'usetax'=>env('companyUseTax'),
            'billtitle'=>env('companyBillTitle'),
            'address'=>env('companyAddress')
        ]);
    }

    public function getCustomers($center_id,Request $request)
    {
        $customers=DB::table('customers')
        ->join('users','users.id','=','customers.user_id')
        ->where('customers.center_id',$center_id)
        ->whereNotIn('users.phone',$request->phones)
        ->select(DB::raw('users.name,users.phone,users.address,customers.panvat,customers.foreign_id as id,
        (select sum(amount) from ledgers where user_id=users.id and type=1) as cr,
        (select sum(amount) from ledgers where user_id=users.id and type=2) as dr'))->get();
        return response()->json($customers);
    }
}
