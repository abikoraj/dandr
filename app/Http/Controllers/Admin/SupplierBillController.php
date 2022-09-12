<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillExpenses;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SupplierBillController extends Controller
{
    public function expense(Request $request)
    {
        $bill_id=Session::get('bill_id');
        if($bill_id==null){
            echo "<script>
                window.close();
            </script>";
        }else{
            $total=0;
            if ($request->getMethod() == "POST") {
                foreach ($request->eis as $value) {
                    $ei = new BillExpenses([
                        'title' => $request->input('ei-title-' . $value),
                        'amount' => $request->input('ei-amount-' . $value),
                        'supplierbill_id' => $bill_id
                    ]);
                    $ei->save();
                    $total+=$ei->amount;
                }
                $date=DB::table('bills')->where('id',$bill_id)->first(['date'])->date;
                new PaymentManager($request,$bill_id,202,'By Purchase Expense A/C',$date);
                Session::remove('bill_id');
            } else {
                return view('admin.supplier.bill.extracharge');
            }

        }
    }
}
