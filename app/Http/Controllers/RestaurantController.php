<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RestaurantController extends Controller
{
    public function table(Request $request)
    {
        if ($request->getMethod() == "POST") {
            DB::update('update tables set data=?  where id = ?', [scriptSafe($request->data), $request->id]);
        } else {
            $items = DB::select('select id,title,sell_price as rate,number  from items where posonly=1');
            $tables = DB::table('tables')->get();
            $sections = DB::table('sections')->get();
            return view('restaurant.table.index', compact('tables', 'sections', 'items'));
        }
    }

    public function bill(Request $request)
    {
        $id = $request->id;
        $table = Table::where('id', $id)->first();
        $datas = json_decode($table->data);
        // dd($datas);
        $items = [];
        $i = 1;
        foreach ($datas as $key => $data) {
            $item_id = $data->item->id;
            if (!isset($items['data_' . $item_id])) {
                $item = DB::selectOne('select sell_price as rate from items where id=?', [$item_id]);

                $items['data_' . $item_id] = (object)[
                    "id" => $item_id,
                    "name" => $data->item->title,
                    "rate" => $item->rate,
                    "qty" => $data->qty,
                    "total" => ($data->item->rate * $data->qty)
                ];
            } else {
                $current = $items['data_' . $item_id];
                $current->qty += $data->qty;
                $current->total = $current->qty  * $current->rate;
                $items['data_' . $item_id] = $current;
            }
        }

       return view('restaurant.table.billitem',compact('items'));
    }

    public function print(Request $request)
    {
        $bill=Bill::where('id',$request->id)->first();
        if($bill->table_id==null){
            echo "invalid bill";
        }else{  
            $bill->billitems=BillItem::where('bill_id',$bill->id)->get();
            return view('restaurant.table.bill',compact('bill'));
        }
    }

    public function kot(Request $request)
    {

        $table=Table::where('id',$request->table_id)->first();
        $datas=json_decode($table->data);
        $localData=[];
        foreach ($datas as $key => $data) {
            if(in_array($data->id,$request->ids)){
                array_push($localData,[$data->id,$data->item->title,$data->qty]);
            }
        }
        return view('restaurant.table.kotprint',compact('localData','table'));
    }


    public function kotDel(Request $request)
    {
        $user = Auth::user();
        if(Hash::check($request->password, $user->password)){
            $table=Table::where('id',$request->table_id)->first();
            $datas=json_decode($table->data);
            $newDatas=[];
            foreach ($datas as $key => $data) {
                if($data->id!=$request->id){
                    array_push($newDatas,$data);
                }
            }
            if(count($newDatas)>0){
                $table->data=json_encode($newDatas);
            }else{
                $table->data=null;
            }
            $table->save();
            return response()->json(['data'=>$table->data,'table_id'=>$request->table_id,'nodel'=>count($newDatas)>0]);
        }else{
            return response()->json(['status'=>false]);
        }
    }
   
}
