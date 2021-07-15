<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Distributer;
use App\Models\User;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(){
        return view('admin.billing.index');
    }

    public function save(Request $request){
        // dd($request->all());
        $bill = new Bill();
        $date = str_replace('-','',$request->date);
        // dd($date);

        if($request->id != -1){
            $distributor = Distributer::where('id',$request->id)->first();
            $user = User::where('id',$distributor->user_id)->first();
            $bill->name = $user->name;
            $bill->address = $user->address;
            $bill->phone = $user->phone;
            $bill->user_id = $user->id;
            $ledger = new LedgerManage($user->id);
            $paidamount=$request->paid>$request->net?$request->net:$request->paid;

        }else{
            $bill->name = 'Cash';
        }
            $bill->grandtotal = $request->gross;
            $bill->paid = $request->paid;
            $bill->due = $request->due;
            $bill->dis = $request->dis;
            $bill->net_total = $request->net;
            $bill->return = $request->return;
            $bill->date = $date;

            $bill->save();

            if($request->id!=-1){
                $ledger->addLedger('Purchase ',1,$request->net,$date,123,$bill->id);

                if($request->paid>0){
                    $ledger->addLedger('Paid Amount',2,$paidamount,$date,122,$bill->id);
                }
            }
            // dd($request->billitems);
            $billitem=[];
            foreach ($request->billitems as $t) {
                // dd($bill->id);
               $item = new BillItem();
               $i=(object)$t;
               $item->product_id = $i->id;
               $item->name = $i->name;
               $item->rate = $i->rate;
               $item->qty = $i->qty;
               $item->total = $i->total;
               $item->bill_id = $bill->id;
               $item->amount = 0;
               $item->save();
               array_push($billitem,$item);
            }
            $bill->items=$billitem;
            return response()->json(['bill'=>$bill]);


    }


}
