<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChalanItem;
use App\Models\EmployeeChalan;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChalanController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->where('role', 4)->get(['id', 'name']);
        return view('admin.chalan.index', compact('users'));
    }

    public function chalanItemSale(Request $request)
    {
        if ($request->isMethod('get')) {
            // $users = User::where('role',4)->get();
            $users = DB::table('users')->where('role', 4)->get(['id', 'name']);
            $centers = DB::table('centers')->get(['id', 'name']);
            $items = DB::table('items')->get(['id', 'title', 'number', 'sell_price']);
            return view('admin.chalan.chalan_sell', compact('centers', 'items', 'users'));
        } else {
            $date = getNepaliDate($request->info['date']);
            $center_id =  $request->info['center_id'];
            $from_employee_id =  $request->info['from_employee_id'];
            // dd($request->items);
            $chalanCheck = EmployeeChalan::where('user_id', $request->info['from_employee_id'])->where('date', $date)->first();
            if ($chalanCheck != null) {
                foreach ($request->items as $key => $item) {

                    //    dd($item_rate->sell_price);
                    $chalanItem = new ChalanItem();
                    $chalanItem->item_id = $item['item_id'];
                    $chalanItem->qty = $item['qty'];
                    $chalanItem->rate = $item['rate'];
                    $chalanItem->employee_chalan_id = $chalanCheck->id;
                    $chalanItem->center_id = $center_id;
                    $chalanItem->save();

                    maintainStock($item['item_id'], $item['qty'], $center_id, 'out');
                }
            } else {
                $chalan =  new EmployeeChalan();
                $chalan->date = $date;
                $chalan->user_id = $from_employee_id;
                $chalan->save();

                foreach ($request->items as $key => $item) {

                    //    dd($item_rate->sell_price);
                    $chalanItem = new ChalanItem();
                    $chalanItem->item_id = $item['item_id'];
                    $chalanItem->qty = $item['qty'];
                    $chalanItem->rate = $item['rate'];
                    $chalanItem->employee_chalan_id = $chalan->id;
                    $chalanItem->center_id = $center_id;
                    $chalanItem->save();

                    maintainStock($item['item_id'], $item['qty'], $center_id, 'out');
                }
            }

            return response()->json(['status' => true]);
        }
    }

    public function ItemList(Request $request)
    {
        $datas = DB::table('employee_chalans')
        ->where('user_id', $request->employee_id)->get();
        $chalanItems = DB::table('chalan_items')
            ->join('items', 'items.id', '=', 'chalan_items.item_id')
            ->select('chalan_items.*', 'items.title')
            ->whereIn('employee_chalan_id', $datas->pluck('id'))->get();
        return view('admin.chalan.data',compact('datas','chalanItems'));
        // $data=DB::select(
        //     "select id,title,date,(select count(*) from chalan_items where employee_chalan_"
        // );
    }
}
