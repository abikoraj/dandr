<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\Milkdata;
use App\Models\Product;
use App\Models\StockOut;
use App\Models\StockOutItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\type;

class MilkController extends Controller
{
    public function index()
    {
        return view('admin.milk.index');
    }

    public function saveMilkData(Request $request, $type)
    {
        // dd($request->all());
        $actiontype = 0;
        $date = str_replace('-', '', $request->date);
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('farmers.no', $request->user_id)->where('farmers.center_id', $request->center_id)->select('users.id', 'farmers.no', 'farmers.center_id')->first();
        // $user=User::where('no',$request->user_id)->first();
        // dd($user,$request);
        if ($user == null) {
            return response("Farmer Not Found", 400);
        } else {
            if ($user->no == null) {
                return response("Farmer Not Found", 500);
            }
        }

        $milkData = Milkdata::where('user_id', $user->id)->where('date', $date)->first();
        if ($milkData == null) {
            $milkData = new Milkdata();
            $milkData->date = $date;
            $milkData->user_id = $user->id;
            $milkData->center_id = $request->center_id;
            $actiontype = 1;
        }

        //request->type 1=save/replace type=2 add
        $product = Item::where('id', env('milk_id'))->first();
        $oldmilk = 0;
        if ($request->session == 0) {
            if ($type == 0) {
                $oldmilk = $milkData->m_amount;
                $milkData->m_amount = $request->milk_amount;
            } else {
                $milkData->m_amount += $request->milk_amount;
            }
        } else {
            if ($type == 0) {
                $oldmilk = $milkData->e_amount;
                $milkData->e_amount = $request->milk_amount;
            } else {
                $milkData->e_amount += $request->milk_amount;
            }
        }

        if ($product != null) {
            if (env('multi_stock')) {

                $centerStock = $product->stock($request->center_id);
                $new = false;
                if ($centerStock == null) {
                    $centerStock = new CenterStock();
                    $centerStock->item_id = $product->id;
                    $centerStock->center_id = $request->center_id;
                    $centerStock->amount = 0;
                    $centerStock->save();
                    $new = true;
                }
            }
            if ($type == 0) {
                $product->stock -= $oldmilk;
                if (!$new && env('multi_stock')) {
                    $centerStock->amount -= $oldmilk;
                }
            }
            $product->stock += $request->milk_amount;
            if (env('multi_stock')) {
                $centerStock->amount += $request->milk_amount;
                $centerStock->save();
            }
            $product->save();
        }
        $milkData->save();
        $milkData->no = $user->no;
        if ($actiontype == 1) {
            return view('admin.milk.single', ['d' => $milkData]);
        } else {
            return response()->json($milkData->toArray());
        }
    }

    public function milkDataLoad(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $milkData = DB::table('milkdatas')
            ->join('farmers', 'farmers.user_id', '=', 'milkdatas.user_id')
            ->where(['date' => $date, 'milkdatas.center_id' => $request->center_id])
            ->select('milkdatas.*', 'farmers.no')
            ->get();
        return view('admin.milk.dataload', ['milkdatas' => $milkData]);
    }

    public function loadFarmerData(Request $request)
    {
        $farmers = User::join('farmers', 'farmers.user_id', '=', 'users.id')->where('farmers.center_id', $request->center)->where('users.role', 1)->select('users.*', 'farmers.center_id')->orderBy('users.no')->get();
        return view('admin.farmer.minlist', compact('farmers'));
    }

    public function update(Request $request)
    {
        $milkdata = Milkdata::find($request->id);
        $milkdata->e_amount = $request->evening;
        $milkdata->m_amount = $request->morning;
        $milkdata->save();
        return response('ok', 200);
    }
    public function delete(Request $request)
    {
        $milkdata = Milkdata::find($request->id);
        $product = Item::where('id', env('milk_id'))->first();
        if ($product != null) {
            $product->stock -= ($milkdata->e_amount + $milkdata->m_amount);
            $product->save();
        }
        $milkdata->delete();
        return response('ok', 200);
    }


    public function chalan(Request $request)
    {
        if ($request->getMethod() == "POST") {
            try {
                $date = str_replace('-', '', $request->date);
                $maincenter = Center::where('id', env('maincenter', null))->first();
                $milk_id = env('milk_id', null);
                if ($maincenter == null) {
                    throw new \Exception('Please Set Main Center');
                }
                if ($milk_id == null) {
                    throw new \Exception('Please Set Milk Item');
                }
                $centers = Center::where('id', '<>', $maincenter->id)->get();

                foreach ($centers as $key => $center) {
                    $center->chalans = DB::select("select 
                    s.id,
                    si.id as stock_out_item_id,
                    si.amount 
                    from stock_outs s 
                    join stock_out_items si on si.stock_out_id=s.id
                    where s.date={$date} and s.from_center_id = {$center->id} and s.center_id={$maincenter->id} and si.item_id={$milk_id}");
                }
                //code...
            } catch (\Throwable $th) {
                return response($th->getMessage());
            }



            // return response()->json($centers);
            return view('admin.milk.chalandata', compact('maincenter', 'centers', 'date'));
        } else {
            return view('admin.milk.chalan');
        }
    }

    public function chalanSave(Request $request)
    {
        $date = $request->date;
        $maincenter = Center::where('id', env('maincenter', null))->first();
        $milk_id = env('milk_id', null);
        if ($maincenter == null) {
            throw new \Exception('Please Set Main Center');
        }
        if ($milk_id == null) {
            throw new \Exception('Please Set Milk Item');
        }

        foreach ($request->chalan_ids as $key => $chalan_id) {
            $amount = $request->input('chalan_amount_' . $chalan_id);
            $stockOutItem = StockOutItem::where('id', $chalan_id)->first();
            if ($stockOutItem->amount != $amount) {
                $stockOut = StockOut::where('id', $stockOutItem->stock_out_id)->first();
                $oldAmount = $stockOutItem->amount;
                $stockOutItem->amount = $amount;
                $stockOutItem->save();
                maintainStockCenter($stockOutItem->item_id, $oldAmount, $stockOut->center_id, "out");
                maintainStockCenter($stockOutItem->item_id, $oldAmount, $stockOut->from_center_id, "in");
                maintainStockCenter($stockOutItem->item_id, $amount, $stockOut->center_id, "in");
                maintainStockCenter($stockOutItem->item_id, $amount, $stockOut->from_center_id, "out");
            }
        }
        foreach ($request->center_ids as $key => $center_id) {
            $amount = $request->input('center_amount_' . $center_id);
            if($amount>0){

                $stockOut = StockOut::create([
                    'date' => $date,
                    'center_id' => $maincenter->id,
                    'from_center_id' => $center_id,
                ]);
    
                $stockOutItem = StockOutItem::create([
                    'item_id' => $milk_id,
                    'amount' => $amount,
                    'stock_out_id' => $stockOut->id
                ]);

                maintainStockCenter($milk_id, $amount, $stockOut->center_id, "in");
                maintainStockCenter($milk_id, $amount, $stockOut->from_center_id, "out");
            }
        }
        return response('ok');
    }
}
