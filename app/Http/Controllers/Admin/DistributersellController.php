<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Distributer;
use App\Models\Distributorsell;
use App\Models\Item;
use App\Models\Ledger;
use App\Models\Sellitem;
use App\Models\User;
use Illuminate\Http\Request;

class DistributersellController extends Controller
{
    public function index()
    {
        $large=env('large',false);
        return view('admin.distributer.sell.index',compact('large'));
    }

    public function addDistributersell(Request $request)
    {
        $date = str_replace('-', '', $request->date);

        // $sell = new Distributorsell();
        // $sell->distributer_id = $request->id;
        // $sell->product_id = $request->product_id;
        // $sell->date = $date;
        // $sell->rate = $request->rate;
        // $sell->qty = $request->qty;
        // $sell->total = $request->total;
        // $sell->paid = $request->paid;
        // $sell->deu = $request->due;
        // $sell->save();

        // $ledger = new LedgerManage($user->user_id);
        // $ledger->addLedger($sell->product->name.' (<span class="d-show-rate">'.$request->rate .' X </span>'.$request->qty.''.$sell->product->unit. ')',1,$request->total,$date,'105',$sell->id);
        // if($request->paid >0){
        //     $ledger->addLedger('Paid amount received',2,$request->paid,$date,'114',$sell->id);
        // }
        $user = Distributer::where('id', $request->id)->first()->user;

        $item = Item::where('dis_number', $request->product_id)->first();
        if ($item == null) {
            $item = Item::where('number', $request->product_id)->first();
        }
        $canadd = false;
        if ($item->trackstock == 1) {
            if ($item->stock > $request->qty) {
                $canadd = true;
            }
        } else {
            $canadd = true;
        }

        if ($canadd) {
            $sell_item = new Sellitem();
            $sell_item->total = $request->total;
            $sell_item->qty = $request->qty;
            $sell_item->rate = $request->rate;
            $sell_item->due = $request->due;
            $sell_item->paid = $request->paid;
            $sell_item->user_id = $user->id;
            $sell_item->item_id = $item->id;
            $sell_item->date = $date;
            $sell_item->save();
            if ($item->trackstock == 1){
                $item->stock = $item->stock - $request->qty;
                $item->save();
            }
            $sell_item->name=$user->name;
            $sell_item->title=$item->title;
            // $sell_item->save();
            $manager = new LedgerManage($user->id);
            if(env('acc_system','old')=='old'){
                $manager->addLedger($item->title . ' ( Rs.' . $sell_item->rate . ' x ' . $sell_item->qty . ')', 1, $request->total, $date, '103', $sell_item->id);
                if ($request->paid > 0) {
                    $manager->addLedger('Paid amount', 2, $request->paid, $date, '114', $sell_item->id);
                }
            }else{
                $manager->addLedger($item->title . ' ( Rs.' . $sell_item->rate . ' x ' . $sell_item->qty . ')', 2, $request->total, $date, '103', $sell_item->id);
                if ($request->paid > 0) {
                    $manager->addLedger('Paid amount', 1, $request->paid, $date, '114', $sell_item->id);
                }
            }
            return view('admin.distributer.sell.single', ['sell' => $sell_item]);
        } else {
            return response('item Stock is not available', 500);
        }
    }

    public function listDistributersell(Request $r)
    {
        $date = str_replace('-', '', $r->date);
        $sells = Sellitem::join('users','users.id','=','sellitems.user_id')
        ->join('items','items.id','=','sellitems.item_id')
        ->where('users.role',2)
        ->where('sellitems.date', $date)->select('users.name','sellitems.*','items.title')->get();
        // dd($sells,$date);
        return view('admin.distributer.sell.list', compact('sells'));
    }

    public function deleteDistributersell(Request $request)
    {
        // $date = str_replace('-','',$request->date);
        $sell = Sellitem::where('id',$request->id)->first();
        if($sell!=null){
            $sell->delete();
        }
        $data = Ledger::where('foreign_key', $request->id)->where('identifire', 103)->first();
        if($data!=null){
            $data->delete();
        }
        $ddd = Ledger::where('foreign_key', $request->id)->where('identifire', 114)->first();
        if ($ddd != null) {
             $ddd->delete();
        }
        return response('ok');
    }
}
