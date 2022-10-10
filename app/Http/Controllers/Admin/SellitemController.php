<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Farmer;
use App\Models\Item;
use App\Models\Ledger;
use App\Models\Sellitem;
use App\Models\User;
use App\NepaliDate;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellitemController extends Controller
{
    public function index()
    {
        $large = env('large', false);
        $hasBatches=[];
        $products=DB::select('select distinct(item_id) from simple_manufacturing_items where batch_no is not null');
        foreach ($products as $key => $product) {
            array_push($hasBatches,$product->item_id);
        }
        return view('admin.sellitem.index', compact('large','hasBatches'));
    }

    public function addSellItem(Request $request)
    {

        $date = getNepaliDate( $request->date);
        $large = env('large', false);

        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('users.no', $request->user_id)->where('farmers.center_id', $request->center_id)->select('users.*', 'farmers.center_id')->first();
        if ($user == null) {
            return response('No User Found With User No ' . $request->user_id, 404);
        }
        $item = Item::where('number', $request->number)->first();
        if ($item == null) {
            return response('No Item Found With Item No ' . $request->user_id, 404);
        }
        // dd($user->id);
        $d = new NepaliDate($date);
        if (!$d->isPrevClosed($user->id)) {
            return response('Previous session is not closed yet', 500);
        }
        $canadd = false;
        $item_center_id=$request->center_id;
        if(env('farmersellmain',false)){
            $item_center_id=env('maincenter');
        }

        $maintainStock=true;
        $extracenters=explode(",",env('extraposcenter',''));
        if(count($extracenters)>0){
            if(in_array($request->center_id,$extracenters)){
                $maintainStock=false;
            }
        }



        if ($item->trackstock == 1 && $maintainStock ) {
            if (env('multi_stock', false)) {
                $stock = $item->stock($item_center_id);
                if ($stock == null) {
                    $canadd = false;
                } else {
                    if ($stock->amount < $request->qty) {
                        $canadd = false;
                    } else {
                        $canadd = true;
                    }
                }
            } else {

                if ($item->stock > $request->qty) {
                    $canadd = true;
                }
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
            if($request->filled('use_batch')){
                $sell_item->batch_id = $request->batch_id;
            }
            // $user = User::where('no',$request->user_id)->first();
            $sell_item->user_id = $user->id;
            $sell_item->item_id = $item->id;
            $sell_item->date = $date;
            $sell_item->center_id = $request->center_id;
            $sell_item->item_center_id = $item_center_id;
            $sell_item->save();

            if ($item->trackstock == 1 && $maintainStock) {
                $item->stock = $item->stock - $request->qty;
                $item->save();
                if (env('multi_stock', false)) {
                    $stock = $item->stock($item_center_id);
                    $stock->amount = $stock->amount - $request->qty;
                    $stock->save();
                }
            }

            
            $manager = new LedgerManage($user->id);
            // $manager->addLedger($item->title . ' ( Rs.' . $sell_item->rate . ' x ' . $sell_item->qty . ')', 1, $request->total, $date, '103', $sell_item->id);
            // if ($request->paid > 0) {
            //     $manager->addLedger('Paid amount', 2, $request->paid, $date, '106', $sell_item->id);
            // }

            
            if(env('acc_system',"old")=="old"){
                $manager->addLedger($item->title.' ( Rs.'.$sell_item->rate.' x '.$sell_item->qty. ')',1,$request->total,$date,'103',$sell_item->id);
                if($request->paid>0){
                    $manager->addLedger('Paid amount',2,$request->paid,$date,'106',$sell_item->id);
                }
            }else{
                $manager->addLedger($item->title.' ( Rs.'.$sell_item->rate.' x '.$sell_item->qty. ')',2,$request->total,$date,'103',$sell_item->id);
                if($request->paid>0){
                    $manager->addLedger('Paid amount',1,$request->paid,$date,'106',$sell_item->id);
                }
            }

            if($sell_item->paid>0){
                new PaymentManager($request,$sell_item->id,106,'To Farmers Sales A/C',$date);
            }

            return view('admin.sellitem.single', compact('sell_item'));
        } else {
            return response('item Stock is not available', 500);
        }
        // LedgerManage::addLedger('Sell Item', 1,$request->total,$date,'101');
    }


    // public function updateSellItem(Request $request){
    //     $date = str_replace('-','',$request->date);
    //     $item_id = Item::where('number',$request->number)->first();
    //     if($item_id->stock>0){
    //         $sell_item = Sellitem::where('id',$request->id)->first();

    //         $preitem = Item::where('id',$sell_item->item_id)->first();
    //         if($request->number == $preitem->number){
    //             if($request->qty > $sell_item->qty){
    //                 $qty = $request->qty - $sell_item->qty;
    //                 $item_id->stock = $item_id->stock - $qty;
    //                 $item_id->save();
    //             }else{
    //                 $qty = $sell_item->qty - $request->qty;
    //                 $item_id->stock = $item_id->stock + $qty;
    //                 $item_id->save();
    //             }
    //         }else{
    //             $preitem->stock = $preitem->stock + $sell_item->qty;
    //             $preitem->save();
    //             $item_id->stock = $item_id->stock - $request->qty;
    //             $item_id->save();
    //         }

    //         $sell_item->total = $request->total;
    //         $sell_item->qty = $request->qty;
    //         $sell_item->rate = $request->rate;
    //         $sell_item->due = $request->due;
    //         $sell_item->paid = $request->paid;

    //         $user = User::where('no',$request->user_id)->first();
    //         $sell_item->user_id = $user->id;

    //         $item_id = Item::where('number',$request->number)->first();
    //         $sell_item->item_id = $item_id->id;
    //         $sell_item->date = $date;
    //         $sell_item->save();
    //         return view('admin.sellitem.single',compact('sell_item'));
    //     }else{
    //         return response()->json('Item Stok is not available');
    //     }
    // }


    public function sellItemList(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $farmer = Farmer::where('center_id', $request->center_id)->select('user_id')->get();
        // $user = User::join('farmers','users.id','=','farmers.user_id')->where('users.no',$request->no)->where('farmers.center_id',$request->center_id)->select('users.*','farmers.center_id')->first();
        $sell = Sellitem::where('date', $date)->whereIn('user_id', $farmer)->get();
        return view('admin.sellitem.list', compact('sell'));
    }

    public function deleteSellitem(Request $request)
    {
        $date = str_replace('-', '', $request->date);

        $sell = Sellitem::find($request->id);
        $item = Item::where('id', $sell->item_id)->first();

        $paid = $sell->paid;
        $total = $sell->total;
        $user_id = $sell->user_id;

        $maintainStock=true;
        $extracenters=explode(",",env('extraposcenter',''));
        if(count($extracenters)>0){
            if(in_array($sell->center_id,$extracenters)){
                $maintainStock=false;
            }
        }
        if($maintainStock){
            maintainStock($sell->item_id,$sell->qty,$sell->item_center_id,"in");
        }

        $sell->delete();
        $manager = new LedgerManage($user_id);
        $ledger = [];
        $ledger[0] = Ledger::where('identifire', '103')->where('foreign_key', $request->id)->first();
        if ($paid > 0) {
            $ledger[1] = Ledger::where('identifire', '106')->where('foreign_key', $request->id)->first();
            PaymentManager::remove($request->id,106);
        }
        LedgerManage::delLedger($ledger);
        return response('Sell Deleted Sucessfully');
    }

    public function multidel(Request $request)
    {
        // dd($request->ids);
        $paymentRemoveArr=[];
        foreach ($request->ids as $id) {


            $sell = Sellitem::find($id);

            $paid = $sell->paid;
            $total = $sell->total;
            $user_id = $sell->user_id;
            $maintainStock=true;
            $extracenters=explode(",",env('extraposcenter',''));
            if(count($extracenters)>0){
                if(in_array($sell->center_id,$extracenters)){
                    $maintainStock=false;
                }
            }
            if($maintainStock){
                maintainStock($sell->item_id,$sell->qty,$sell->item_center_id,"in");
            }
            $sell->delete();
            $manager = new LedgerManage($user_id);
            $ledger = [];
            echo "id:" . $id . ", paid:" . $paid . "<br>";
            $ledger1 = Ledger::where('user_id', $user_id)->where('identifire', '103')->where('foreign_key', $id)->first();
            if ($ledger1 != null) {
                array_push($ledger, $ledger1);
            }

            echo "step1 <br>";

            if ($paid > 0) {
                array_push($paymentRemoveArr,$id);
                $ledger2 = Ledger::where('user_id', $user_id)->where('identifire', '106')->where('foreign_key', $id)->first();
                if ($ledger2 != null) {
                    array_push($ledger, $ledger1);

                }
                echo "step2 <br>";
                PaymentManager::remove($id,106);
            }
            // $ledger[0] = Ledger::where('identifire','106')->where('foreign_key',$request->id);
            LedgerManage::delLedger($ledger);
        }
    }
}
