<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChalanItem;
use App\Models\ChalanSale;
use App\Models\EmployeeChalan;
use App\Models\Item;
use App\Models\User;
use App\NepaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChalanController extends Controller
{
    public function index()
    {
        $today=nepaliToday();

        $users = DB::table('users')->where('role', 4)->get(['id', 'name']);
        return view('admin.chalan.index', compact('users'));
    }


   

    public function chalanItemSale(Request $request)
    {
        if ($request->isMethod('get')) {
            // $users = User::where('role',4)->get();
            $users = DB::table('users')->where('role', 4)->get(['id', 'name']);
            $centers = DB::table('centers')->get(['id', 'name']);
            $items = DB::table('items')->get(['id', 'title', 'number', 'sell_price','wholesale']);
            $centerStocks=DB::table('center_stocks')->whereIn('item_id',$items->pluck('id'))->get(['item_id','center_id','amount']);
            return view('admin.chalan.chalan_sell', compact('centers', 'items', 'users','centerStocks'));

        } else {
            $date = getNepaliDate($request->info['date']);
            $center_id =  $request->info['center_id'];
            $from_employee_id =  $request->info['from_employee_id'];
            $chalan = EmployeeChalan::where('user_id', $request->info['from_employee_id'])->where('date', $date)->first();
            if ($chalan == null) {
                $chalan =  new EmployeeChalan();
                $chalan->date = $date;
                $chalan->user_id = $from_employee_id;
                if(isSuper()){
                    $chalan->approved=1;
                    $chalan->approvedBy=Auth::user()->name;
                }
                $chalan->save();
            }else{
                if($chalan->approved==1){
                    throw new \Exception('Chalan already approved.');
                }
                if($chalan->closed==1 ){
                    throw new \Exception('Chalan already closed for this employee.');
                }
            }

            

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

            return response()->json(['status' => true]);
        }
    }

    public function ItemList(Request $request)
    {
        $datasQuery = DB::table('employee_chalans')
        ->join('users','users.id','=','employee_chalans.user_id');
        if($request->filled('employee_id')){
            if($request->employee_id>0){
                $datasQuery=$datasQuery->where('user_id', $request->employee_id);
            }
        }
        if($request->filled('date')){
            $date=getNepaliDate($request->date);
            $datasQuery=$datasQuery->where('date', $date);
        }
        $datas=  $datasQuery->get(['employee_chalans.*','users.name']);

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
        $chalan=DB::table('employee_chalans')->where('id',$id)->first();
        if($chalan->closed==1){
            return redirect()->route('admin.chalan.chalan.final.details',['id'=>$id]);
        }
        $customers = DB::table('users')
        ->join('customers','users.id','customers.user_id')->select('users.name','users.id')->get()->toArray();
        $distributors = DB::table('users')
        ->join('distributers','users.id','distributers.user_id')->select('users.name','users.id')->get()->toArray();
        $users = array_merge($customers,$distributors);
        $datas = DB::table('employee_chalans')
        ->where('id', $id)->first();
        $items = DB::table('chalan_items')->where('employee_chalan_id',$datas->id)
        ->join('items','items.id','=','chalan_items.item_id')->select('chalan_items.id','chalan_items.item_id','items.title','chalan_items.rate')
        ->get();
        // dd($items);
        $wastageItem = DB::table('chalan_items')->where('chalan_items.wastage','>',0)->where('chalan_items.employee_chalan_id',$id)
        ->join('items','items.id','=','chalan_items.item_id')->select('chalan_items.id','items.title','chalan_items.wastage')->get();
        return view('admin.chalan.detail',compact('users','datas','items','wastageItem'));
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

    public function chalanWastage(Request $request){
        // dd($request->all());
        $chalanItem = ChalanItem::where('id',$request->chalan_item_id)->first();
        if($chalanItem->wastage>0){
            $chalanItem->wastage += $request->wastage_qty;
            $chalanItem->save();
        }else{
            $chalanItem->wastage = $request->wastage_qty;
            $chalanItem->save();
        }
        $data = DB::table('chalan_items')->where('chalan_items.id',$request->chalan_item_id)
        ->join('items','items.id','=','chalan_items.item_id')->select('chalan_items.id','items.title','chalan_items.wastage')->first();
        return view('admin.chalan.wastage.single',compact('data'));
    }

    public function chalanWastageDelete(Request $request){
        $chalanItem = ChalanItem::where('id',$request->id)->first();
        $chalanItem->wastage = 0;
        $chalanItem->save();
    }

    public function chalanFinalDetails($id)
    {
        $chalan = EmployeeChalan::where('id', $id)->first();
        $payments = DB::table('chalan_payments')->where('employee_chalan_id', $id)->get();
        $chalanItems = DB::table('chalan_items')
            ->join('items', 'items.id', '=', 'chalan_items.item_id')
            ->where('employee_chalan_id', $id)
            ->select('chalan_items.*', 'items.title', 'items.unit')
            ->get();
        $sellItems = DB::table('chalan_sales')
            ->join('items', 'items.id', '=', 'chalan_sales.item_id')
            ->where('employee_chalan_id', $id)
            ->select('chalan_sales.*', 'items.title', 'items.unit')
            ->get();
        $user_ids = array_merge($payments->pluck('user_id')->toArray(), $sellItems->pluck('user_id')->toArray());

        $users = DB::table('users')->whereIn('id', $user_ids)->get(['id', 'name']);

        foreach ($users as $key => $user) {
            $user->sales = $sellItems->where('user_id', $user->id);
            $user->payments = $payments->where('user_id', $user->id);
            $user->sales_amount = $sellItems->where('user_id', $user->id)->sum('total');
            $user->payments_amount = $payments->where('user_id', $user->id)->sum('amount');
            $balance = $user->sales_amount - $user->payments_amount;
            $user->due = $balance > 0 ? $balance : 0;
            $user->balance = $balance < 0 ? (-1 * $balance) : 0;
        }

        foreach ($chalanItems as $key => $chalanItem) {
            $chalanItem->sold = $sellItems->where('item_id', $chalanItem->item_id)->sum('qty');
            $chalanItem->newremaning = $chalanItem->qty - $chalanItem->sold - $chalanItem->wastage;
        }
        return view('admin.chalan.closing.detail',compact('chalan', 'users', 'chalanItems'));
    }

    public function approve(Request $request,$id)
    {
        if($request->getMethod()=="POST"){
            DB::update('update employee_chalans set approved=1 where id=?', [$id]);
            return redirect()->route('admin.chalan.index');
        }else{
            $chalan=DB::selectOne('select c.*,u.name from employee_chalans c join users u on c.user_id=u.id where c.id=?',[$id]);
            if($chalan->approved==1){
                return redirect()->route('admin.chalan.index');
            }

            $items=DB::select('select c.*,i.title from chalan_items c join items i on c.item_id=i.id where c.employee_chalan_id=?',[$id]);
            return view('admin.chalan.approve.index',compact('chalan','items'));
        }
    }

    public function print($id)
    {

        $customers=getUsers(['customers','distributers'],['name','phone']);
        // dd($customers);
        $chalan=DB::selectOne('select c.*,u.name from employee_chalans c join users u on c.user_id=u.id where c.id=?',[$id]);
        $items=DB::select('select c.*,i.title from chalan_items c join items i on c.item_id=i.id where c.employee_chalan_id=?',[$id]);
        return view('admin.chalan.print.index',compact('chalan','items','customers'));
    }

    public function edit(Request $request,$id)
    {
        if($request->getMethod()=="POST"){
            // dd($request->all());
            DB::update('update chalan_items set qty=?,rate=? where id=?',[$request->qty,$request->rate,$id]);

        }else{
            $chalan=DB::selectOne('select c.*,u.name from employee_chalans c join users u on c.user_id=u.id where c.id=?',[$id]);
            $items=DB::select('select c.*,i.title from chalan_items c join items i on c.item_id=i.id where c.employee_chalan_id=?',[$id]);
            return view('admin.chalan.edit.index',compact('chalan','items'));

        }
    }

    public function del(Request $request)
    {
        if($request->getMethod()=="POST"){
            if(DB::table('chalan_sells')->where('chalan_item_id',$request->id)->count()>0){
                throw new \Exception('Chalan item already used.');
            }
            DB::update('delete from chalan_items where id=?',[$request->id]);
        }
    }

}
