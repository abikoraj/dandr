<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        ->where('customers.foreign_id','<>',0)
        ->select(DB::raw('users.name,users.phone,users.address,customers.panvat,customers.foreign_id as id,
        (select sum(amount) from ledgers where user_id=users.id and type=1) as cr,
        (select sum(amount) from ledgers where user_id=users.id and type=2) as dr'))->get();
        return response()->json($customers);
    }

    public function customerList(){
        $customers = DB::table('customers')
        ->join('users','users.id','=','customers.user_id')
        ->select(['users.name','users.phone'])->get();
        return response()->json($customers);
    }

    public function employeeChalan(Request $request){
        $date = getNepaliDate($request->date);
        $user = Auth::user();
        $data = DB::table('employee_chalans')->where('user_id',$user->id)->where('date',$date)
        ->first();
        if($data != null){
        $chalanItems = DB::table('chalan_items')->where('employee_chalan_id',$data->id)
        ->join('items','items.id','=','chalan_items.item_id')
        ->select('items.title','chalan_items.id','chalan_items.rate','chalan_items.item_id')->get();
            return response()->json($chalanItems);
        }else{
            return response()->json([
                'message' => 'Record Not Found'
            ]);
        }
    }
}
