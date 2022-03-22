<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Ledger;
use App\Models\PosBill;
use App\Models\PosBillItem;
use App\Models\PosSetting;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //
    public function index(Request $request)
    {
        $items = DB::select('select id,title,sell_price,wholesale,stock ,number as barcode,unit,taxable,tax,
        (select IFNULL(sum(amount),0) from center_stocks where item_id=items.id and center_id=?) as center_stock,
        (select IFNULL(sum(rate),0) from center_stocks where item_id=items.id and center_id=?) as center_rate,
        (select IFNULL(sum(wholesale),0) from center_stocks where item_id=items.id and center_id=?) as center_wholesale
        from items ', [$request->center_id, $request->center_id, $request->center_id]);

        return response(json_encode($items, JSON_PRESERVE_ZERO_FRACTION));
    }

    public function syncBills(Request $request)
    {
        $pointSetting=getSetting('point')??(object)([
            'type'=>0,
            'point'=>0,
            'per'=>0
        ]);
        $setting = PosSetting::first();
        $fy = $setting->fiscalYear();
        $bill = new PosBill();

        $_bill = (object)$request->bill;
        $bis = [];
        $items = [];
        $centers = [];
        $counter = Counter::where('id', $_bill->counter_id)->first();
        $status = $counter->currentStatus($_bill->date);
        try {
            if (PosBill::where('fiscal_year_id', $fy->id)->where('bill_no', $_bill->bill_no)->count() > 0) {
                return response()->json([
                    'status' => true,
                    'msg' => "Bill Saved Sucessfully",
                    'bill_id' => PosBill::where('fiscal_year_id', $fy->id)->where('bill_no', $_bill->bill_no)->select('id')->first()->id
                ]);
            }
            $point=0;
            $bill->bill_no = $_bill->bill_no;
            $bill->date = $_bill->date;
            $bill->counter_id = $_bill->counter_id;
            $bill->center_id = $request->center_id;
            $bill->counter_name = $_bill->counter_name;
            $bill->fiscal_year_id = $fy->id;

            if ($_bill->customer_id != null) {
                $customer = (object)$request->customer;

                $new_cus = Customer::where('foreign_id', $customer->id)->where('center_id', $request->center_id)->first();
                if ($new_cus == null) {
                    $cus_user = User::where('phone', $customer->phone)->first();
                    if ($cus_user == null) {
                        $cus_user = new User();
                        $cus_user->password = bcrypt($customer->phone);
                        $cus_user->role = 5;
                        $cus_user->save();
                    }
                    $cus_user->name = $customer->name;
                    $cus_user->address = $customer->address;
                    $cus_user->phone = $customer->phone;
                    $cus_user->save();

                    $new_cus = new Customer();
                    $new_cus->panvat = $customer->panvat;
                    $new_cus->center_id = $request->center_id;
                    $new_cus->user_id = $cus_user->id;
                    $new_cus->foreign_id = $customer->id;
                    $new_cus->save();
                }
                // $cus_user=User::where('phone',$request)
                $bill->customer_name = $customer->name;
                $bill->customer_address = $customer->address;
                $bill->customer_phone = $customer->phone;
                $bill->customer_pan = $customer->panvat;
                $bill->customer_id = $new_cus->id;
            } else {
                $bill->customer_name = $_bill->customer_name;
            }
            $bill->total = $_bill->total;
            $bill->discount = $_bill->discount;
            $bill->taxable = $_bill->taxable;
            $bill->tax = $_bill->tax;
            $bill->rounding = $_bill->rounding;
            $bill->grandtotal = $_bill->grandtotal;
            $bill->paid = $_bill->paid;
            $bill->due = $_bill->due;
            $bill->return = $_bill->return;
            $bill->sync_id = $_bill->id;
            $user = User::where('phone', $_bill->user_id)->first();
            if ($user == null) {
                $user = Auth::user();
            }
            $bill->user_id = $user->id;
            $bill->save();
            if($pointSetting->type==1){
                $point=$bill->grandtotal/$pointSetting->per*$pointSetting->point;
            }

            foreach ($request->items as $key => $_bi) {
                if ($_bi != null) {
                    $bi = new PosBillItem();
                    $bi->pos_bill_id = $bill->id;
                    $bi->qty = $_bi['qty'];
                    $bi->rate = $_bi['rate'];
                    $bi->name = $_bi['name'];
                    $bi->item_id = $_bi['item_id'];
                    $bi->amount = $_bi['amount'];
                    $bi->discount = $_bi['discount'];
                    $bi->taxable = $_bi['taxable'];
                    $bi->tax = $_bi['tax'];
                    $bi->tax_per = $_bi['tax_per'];
                    $bi->total = $_bi['total'];
                    $bi->use_tax = $_bi['use_tax'];
                    $item = Item::where('id', $_bi['item_id'])->select('id', 'title', 'wholesale', 'sell_price', 'stock', 'trackstock','points')->first();
                    if ($item->trackstock == 1) {
                        $item->stock -= $bi->qty;
                        $item->save();
                        if($pointSetting->type==2){
                            $point+=$bi->qty*$item->points;
                        }
                        array_push(
                            $items,
                            [
                                'item' => $item,
                                'qty' => $bi->qty
                            ]
                        );
                        $center_stock = CenterStock::where('center_id', $request->center_id)->where('item_id', $item->id)->first();
                        if ($center_stock == null) {
                            $center_stock = new CenterStock();
                            $center_stock->center_id = $request->center_id;
                            $center_stock->item_id = $item->id;
                            $center_stock->wholesale = $item->wholesale;
                            $center_stock->rate = $item->sell_price;
                            $center_stock->amount = -1 * $bi->qty;
                            $center_stock->save();
                        } else {
                            $center_stock->amount -= $bi->qty;
                            $center_stock->save();
                        }
                        array_push($centers, [
                            'stock' => $center_stock,
                            'qty' => $bi->qty
                        ]);
                    }
                    $bi->save();
                    array_push($bis, $bi);
                }
            }

            $bill->points=$point;
            $bill->save();

            if ($bill->customer_id != null) {

                if($point>0){
                    DB::update('update customers set points = ifnull(points,0) + ? where id = ?', [$point,$bill->customer_id]);
                }
            }
            $status->current += $bill->grandtotal;
            $status->save();
        } catch (\Throwable $th) {
            if ($bill->id != null && $bill->id != 0) {
                foreach ($items as $key => $item_holder) {
                    $item = $item_holder['item'];
                    $item->stock += $item_holder['qty'];
                    $item->save();
                }
                foreach ($centers as $key => $center_holder) {
                    $center_Stock = $center_holder['qty'];
                    $center_Stock->amount += $center_holder['qty'];
                    $center_Stock->save();
                }

                DB::table('pos_bill_items')->where('pos_bill_id', $bill->id)->delete();
                $bill->delete();
                return response()->json([
                    'status' => false,
                    'msg' => "Bill Cannot be Saved, " . $th->getMessage()
                ]);
            }
        }
        return response()->json([
            'status' => true,
            'msg' => "Bill Saved Sucessfully",
            'bill_id' => $bill->id
        ]);
    }

    public function syncLedger(Request $request)
    {
        $customer = (object)$request->customer;
        $ledger = (object) $request->ledger;
        $new_cus = Customer::where('foreign_id', $customer->id)->where('center_id', $request->center_id)->first();
        if ($new_cus == null) {
            $cus_user = User::where('phone', $customer->phone)->first();
            if ($cus_user == null) {
                $cus_user = new User();
                $cus_user->password = bcrypt($customer->phone);
                $cus_user->role = 5;

            }
            $cus_user->name = $customer->name;
            $cus_user->address = $customer->address;
            $cus_user->phone = $customer->phone;
            $cus_user->save();

            $new_cus = new Customer();
            $new_cus->panvat = $customer->panvat;
            $new_cus->center_id = $request->center_id;
            $new_cus->user_id = $cus_user->id;
            $new_cus->foreign_id = $customer->id;
            $new_cus->save();
        }
        try {
            $nepalidate = new NepaliDate($ledger->date);
            $l = new \App\Models\Ledger();
            $l->amount = $ledger->amount;
            $l->title = $ledger->particular;
            $l->date = $ledger->date;
            $l->identifire = $ledger->identifire;
            $l->foreign_key = $ledger->foreign_id;
            $l->user_id = $new_cus->user_id;
            $l->year = $nepalidate->year;
            $l->month = $nepalidate->month;
            $l->session = $nepalidate->session;
            $l->type = $ledger->type;
            $l->out=1;
            $l->save();
            return response()->json([
                'status' => true,
                'msg' => "Bill Saved Sucessfully",
                'ledger_id' => $l->id
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage(),
                'ledger_id' => null
            ]);
        }


        return response($l);
    }
}
