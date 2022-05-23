<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Conversion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversionController extends Controller
{
    //

    public function index()
    {
        $conversions = DB::table('conversions')->select(DB::raw('id,local,main,name,parent_id,is_base,(select count(*) from items where conversion_id=conversions.id) as used'))->get();
        // dd($conversions->where('parent_id',1));
        return view('admin.setting.conversion.index', compact('conversions'));
    }

    public function add(Request $request)
    {
        $conversion = new Conversion();
        $conversion->name = $request->name;
        $conversion->is_base = true;
        $conversion->save();
        return redirect()->back();
    }
    public function update(Request $request)
    {
        $conversion = Conversion::where('id', $request->id)->first();
        $conversion->name = $request->name;
        $conversion->save();
        return response('ok');
    }

    private function getChildrens($coll, $arr = [])
    {
        if (count($coll) > 0) {
            foreach ($coll as $key => $data1) {

                $coll1 = DB::table('conversions')
                    ->where('parent_id', $data1->id)
                    ->select(DB::raw('id,(select count(*) from items where conversion_id=conversions.id) as used'))
                    ->get();
                if($data1->used>0){
                    throw new \Exception("Conversion Currenty in use", 1);

                }else{

                    array_push($arr,$data1->id);
                }
                $arr=$this->getChildrens($coll1,$arr);
            }
            return $arr;

        }else{
            return $arr;
        }
    }

    public function del(Request $request)
    {
        $delArray = [];
        $conversion = Conversion::where('id', $request->id)
            ->select(DB::raw('id,(select count(*) from items where conversion_id=conversions.id) as used'))
            ->first();
        if ($conversion->used > 0) {
            throw new \Exception("Conversion Currently In Use", 1);
        } else {

            array_push($delArray, $conversion->id);
        }
        $conversions = DB::table('conversions')
            ->where('parent_id',$request->id)
            ->select(DB::raw('id,(select count(*) from items where conversion_id=conversions.id) as used'))
            ->get();


        $data=$this->getChildrens($conversions,$delArray);
        DB::table('conversions')->whereIn('id',$data)->delete();
        return response('ok');
        // foreach ($conversions as $key => $data) {
        //     $conversion1 = DB::table('conversions')
        //         ->where('parent_id', $data->id)
        //         ->select(DB::raw('id,(select count(*) from items where conversion_id=conversions.id) as used'))
        //         ->get();
        //     if (count($conversion1) > 0) {
        //         foreach ($conversion1 as $key => $data1) {
        //             $conversion2 = DB::table('conversions')
        //                 ->where('parent_id', $data1->id)
        //                 ->select(DB::raw('id,(select count(*) from items where conversion_id=conversions.id) as used'))
        //                 ->get();
        //             foreach ($conversion2 as $key => $data2) {
        //                 if ($data2->used > 0) {
        //                     // throw new \Exception("Already in Use", 1);
        //                 } else {
        //                     array_push($delArray, $data2->id);
        //                 }
        //             }
        //             if ($data1->used > 0) {
        //                 // throw new \Exception("Already in Use", 1);
        //             } else {
        //                 array_push($delArray, $data1->id);
        //             }
        //         }
        //     }
        //     if ($data->used > 0) {
        //         // throw new \Exception("Already in Use", 1);
        //     } else {
        //         array_push($delArray, $data->id);
        //     }
        // }
        // dd($delArray);

    }

    public function addSub(Request $request)
    {
        $conversion = new Conversion();
        $conversion->name = $request->name;
        $conversion->main = $request->main;
        $conversion->local = $request->local;
        $conversion->parent_id = $request->parent_id;
        $conversion->is_base = false;
        $conversion->save();
        return view('admin.setting.conversion.subunitsingle', ['conversion' => $conversion, 'baseUnit' => $request->baseUnit]);
    }
    public function updateSub(Request $request)
    {
        $conversion = Conversion::where('id', $request->id)->first();
        $conversion->name = $request->name;
        $conversion->main = $request->main;
        $conversion->local = $request->local;
        $conversion->save();
        return view('admin.setting.conversion.subunitsingle', ['conversion' => $conversion, 'baseUnit' => $request->baseUnit]);
    }
}
