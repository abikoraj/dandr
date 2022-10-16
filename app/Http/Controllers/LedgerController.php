<?php

namespace App\Http\Controllers;

use App\LedgerManage;
use App\Models\Advance;
use App\Models\DistributorPayment;
use App\Models\Distributorsell;
use App\Models\EmployeeAdvance;
use App\Models\Farmerpayment;
use App\Models\Ledger;
use App\Models\MilkPayment;
use App\Models\Sellitem;
use App\Models\Supplierbill;
use App\Models\Supplierpayment;
use App\Models\User;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LedgerController extends Controller
{
    /*
    *amounttype[1="CR",2="DR"]
    * "101"= Aalya --
    * "102"= "farmer opening balance/advance" --
    * "103" = "item sell" --
    * "104" = "Farmer Advance"--
    * "106" = "Farmer amount paid at Selling item"v--
    * "107" = "Amount paid by farmer" --
    * "108" = "Payment For Milk" --
    * "109" = "Closing Balance Farmer" --
    * "110" = "Automatic payment Given to famer when closing" --
    * "116" = "Farmer item return"
    * "117" = "Farmer item return paid cancel"
    * "121" = "Farmer paid for milk" --
    * "124" = "Farmer Bonus" --

    * "114" = "distributer Payment" --
    * "115" = "distributer sell cancel" --
    * "118" = "Account Adjustment"
    * "119" = "Distributor opening balance"
    * "120" = ""

    * "112" = "Employee Advaance payment"
    * "113" = "Employee Advaance payment cancel"
    * "124" = "Employee Salary payment"
    * "122" = "paid amount while billing"
    * "123" = "purchase in billing items"



    * "125" = "purchase from suppliers"
    * "126" = "paid to suppliers through billing entry"
    * "127" = "Payment to supplier"
    * "128" = "Previous balance of supplier"

    */

    const changable=[102,119,113,128,134];

    public function edit(Request $request){
        $ledger=Ledger::where('id',$request->id)->first();
        if($ledger->identifire==103){
            $sellitem=Sellitem::where('id',$ledger->foreign_key)->first();
            return view('admin.ledger.sellitem-edit',compact('ledger','sellitem'));
        }else if($ledger->identifire==125 || $ledger->identifire==126){
            return response("<h5 class='text-center'>Supplier Bill Cannot Be Edited From This interface</h5>");
        }else{
            $paymentData=$ledger->getPaymentData();

            return view('admin.ledger.edit',compact('ledger','paymentData'));
        }
    }

    public function update(Request $request){
        $ledger=Ledger::where('id',$request->id)->first();
        $user=User::find($ledger->user_id);
        $title=$ledger->title;
        $i=$ledger->identifire;
        $key=$ledger->foreign_key;
        $type=$request->type;
        if($i==103){
            $type=2;
            $sellitem=Sellitem::where('id',$key)->first();
            $title=$sellitem->item->name.' ('.$request->rate .' X '.$request->qty.''.$sellitem->item->unit. ')';
            $sellitem->rate=$request->rate;
            $sellitem->qty=$request->qty;
            $sellitem->total=$request->amount;
            $sellitem->due = $sellitem->total - $sellitem->paid;
            $sellitem->save();
        }
        else if($i==106){
            $type=1;
            $sellitem=Sellitem::where('id',$key)->first();
            $sellitem->paid=$request->amount;
            $sellitem->due = $sellitem->total - $sellitem->paid;
            $sellitem->save();

        }else if($i==104){
            $type=2;
            $advance=Advance::where('id',$key)->first();
            $advance->amount=$request->amount;
            $advance->save();
        }else if($i==107){
            $type=1;
            $advance=Farmerpayment::where('id',$key)->first();
            $advance->amount=$request->amount;
            $advance->save();


        }else if($i==121){
            $type=2;
            $payment=MilkPayment::where('id',$key)->first();
            $payment->amount=$request->amount;
            $payment->save();
        }else if($i==150){
            $type=1;
            $payment=DistributorPayment::where('id',$key)->first();
            $payment->amount=$request->amount;
            $payment->save();
        }else if($i==112){
            $type=2;
            $payment=EmployeeAdvance::where('id',$key)->first();
            $payment->amount=$request->amount;
            $payment->save();
        }else if($i==127){
            $type=2;
            $payment=Supplierpayment::where('id',$key)->first();
            $payment->amount=$request->amount;
            $payment->save();
        }

        $ledger->updatePayment($request);

        $ledger->type=$type;
        $ledger->amount=$request->amount;
        $ledger->title=$title;
        $ledger->save();



    }

    public function del(Request $request){
        $ledger=Ledger::where('id',$request->id)->first();
        $user=User::find($ledger->user_id);
        $i=$ledger->identifire;
        $key=$ledger->foreign_key;
        $foreign=null;
        $another=null;
        if($i==103){
            $foreign=Sellitem::where('id',$key)->first();
            $another=Ledger::where('ad');
        }else if($i==104){
            $foreign=Advance::where('id',$key)->first();
        }else if($i==107){
            $foreign=Farmerpayment::where('id',$key)->first();
        }else if($i==121){
           $foreign=MilkPayment::where('id',$key)->first();
        }else if($i==112){
            $foreign=EmployeeAdvance::where('id',$key)->first();
         }else if($i==150){
           $foreign=DistributorPayment::where('id',$key)->first();
        }else if($i==127){
           $foreign=Supplierpayment::where('id',$key)->first();
        }

        if($foreign!=null){
            $foreign->delete();
        }
        if($another!=null){
            $another->delete();
        }
        $ledger->deletePayment();
        $ledger->delete();

        return response('ok');
    }

    public function balance(Request $request)
    {
        return response()->json(DB::selectOne('select (ifnull((select sum(amount) from ledgers where user_id=? and type=2),0)
        - ifnull((select sum(amount) from ledgers where user_id=? and type=1),0) ) as balance  ', [$request->id,$request->id]));
    }
}
