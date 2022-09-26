<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChalanItem;
use App\Models\ChalanSale;
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

    public function chalanDetails($id){
        $customers = DB::table('users')
        ->join('customers','users.id','customers.user_id')->select('users.name','users.id')->get()->toArray();
        $distributors = DB::table('users')
        ->join('distributers','users.id','distributers.user_id')->select('users.name','users.id')->get()->toArray();
        $users = array_merge($customers,$distributors);
        $datas = DB::table('employee_chalans')
        ->where('id', $id)->first();
        $items = DB::table('chalan_items')->where('employee_chalan_id',$datas->id)
        ->join('items','items.id','=','chalan_items.item_id')->select('chalan_items.item_id','items.title','chalan_items.rate')
        ->get();
        // dd($items);
        return view('admin.chalan.detail',compact('users','datas','items'));
    }

    public function chalanSave(Request $request){
        // dd($request->all());
        $date = getNepaliDate($request->date);
        $sell = new ChalanSale();
        $sell->total = $request->total;
        $sell->qty = $request->qty;
        $sell->rate = $request->rate;
        $sell->paid = 0;
        $sell->due = 0;
        $sell->date = $date;
        $sell->user_id = $request->user_id;
        $sell->employee_chalan_id = $request->employee_chalan_id;
        $sell->item_id = $request->item_id;
        $sell->save();
        $name = $request->item_name;
        $user = DB::table('users')->where('id',$sell->user_id)->select('name')->first();
        return view('admin.chalan.sell_data',compact('sell','name','user'));

    }

    public function chalanList(Request $request){

        $sells = DB::table('chalan_sales')->where('employee_chalan_id',$request->employee_chalan_id)
        ->join('users','users.id','=','chalan_sales.user_id')
        ->join('items','items.id','=','chalan_sales.item_id')
        ->select('chalan_sales.id','chalan_sales.rate','chalan_sales.qty','chalan_sales.total','users.name','items.title')->get();
        // dd($sells);
        return view('admin.chalan.sell_datas',compact('sells'));
    }


    public function chalanDelete(Request $request){
        DB::table('chalan_sales')->where('id',$request->id)->delete();
    }
}
