<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Distributer;
use App\Models\Item;
use App\Models\Sellitem;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeSalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $date = str_replace('-', '', $request->date);

            $sell_items=DB::select('select s.id,s.user_id,s.rate,s.qty,s.total,s.paid,s.due,u.name,i.title as item from
             sellitems s join users u on s.user_id=u.id 
             join employees e on e.user_id=s.user_id 
             join items i on s.item_id=i.id 

             where s.date=?
            ',[$date]);
            return view('admin.emp.sales.data',compact('sell_items'));
        } else {
            $centers = DB::table('centers')->get(['id', 'name']);
            $emps = DB::table('users')->join('employees', 'employees.user_id', '=', 'users.id')
                ->select('users.id', 'users.name')->get();
            $items = DB::table('items')->select('id', 'title', 'sell_price')->get();
            return view('admin.emp.sales.index', compact('centers', 'emps', 'items'));
        }
    }

    public function save(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $user = DB::table('users')->where('id', $request->employee_id)->first();

        $item = Item::where('id', $request->item_id)->first();
        if ($item == null) {
            throw new \Exception("Item Not Found");
        }

        $canadd = false;

        if ($item->trackstock == 1) {
            if ($item->stock >= $request->qty) {
                if (env('multi_stock', false)) {
                    $stock = $item->stock($request->center_id);
                    $canadd = $stock == null ? false : ($stock->amount >= $request->qty);
                } else {
                    $canadd = true;
                }
            }
        } else {
            $canadd = true;
        }

        if (!$canadd) {
            throw new \Exception('Stock Not Enough');
        }

        $sell_item = new Sellitem();
        $sell_item->total = $request->total;
        $sell_item->qty = $request->qty;
        $sell_item->rate = $request->rate;
        $sell_item->due = $request->due;
        $sell_item->paid = $request->paid;
        $sell_item->center_id = $request->center_id;
        $sell_item->user_id = $user->id;
        $sell_item->item_id = $item->id;
        $sell_item->date = $date;
        $sell_item->save();
        if ($item->trackstock == 1) {
            maintainStock($item->id, $request->qty, $request->center_id, "out");
        }
        $sell_item->name = $user->name;
        $sell_item->title = $item->title;
        // $sell_item->save();
        $manager = new LedgerManage($user->id);
        if (env('acc_system', 'old') == 'old') {
            $manager->addLedger($item->title . ' ( Rs.' . $sell_item->rate . ' x ' . $sell_item->qty . ')', 1, $request->total, $date, '301', $sell_item->id);
            if ($request->paid > 0) {

                $manager->addLedger('Paid amount', 2, $request->paid, $date, '302', $sell_item->id);
            }
        } else {
            $manager->addLedger($item->title . ' ( Rs.' . $sell_item->rate . ' x ' . $sell_item->qty . ')', 2, $request->total, $date, '301', $sell_item->id);
            if ($request->paid > 0) {
                $manager->addLedger('Paid amount', 1, $request->paid, $date, '302', $sell_item->id);
            }
        }


        new PaymentManager($request, $sell_item->id, 301);
        $sell_item->item=$item->title;
        $sell_item->name=$user->name;
        return view('admin.emp.sales.single',compact('sell_item'));
    }

    public function del(Request $request)
    {
        $sell_item=DB::table('sellitems')->where('id', $request->id)->first(['item_id','center_id','qty']);
        maintainStock($sell_item->item_id,$sell_item->qty,$sell_item->center_id,'in');
        DB::table('sellitems')->where('id', $request->id)->delete();
        DB::delete('delete from ledgers where foreign_key = ? and (identifire=301 or identifire=302)', [$request->id]);
    }
}
