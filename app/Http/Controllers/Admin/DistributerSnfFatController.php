<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributersnffat;
use App\Models\User;
use Illuminate\Http\Request;

class DistributerSnfFatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $date = str_replace('-', '', $request->date);
            $data = Distributersnffat::where('date', $date)
                ->join('distributers', 'distributers.id', '=', 'distributersnffats.distributer_id')
                ->join('users', 'distributers.user_id', '=', 'users.id')
                ->select('distributersnffats.*', 'users.name');
            $milkDatas = $data->get();
            return view('admin.distributer.snffat.list', compact('milkDatas'));
        } else {
            return view('admin.distributer.snffat.index');
        }
    }

    public function add(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $milkData = new Distributersnffat();
        $milkData->distributer_id = $request->id;
        $milkData->snf = $request->snf;
        $milkData->fat = $request->fat;
        $milkData->date = $date;
        $milkData->save();
        $user = User::join('distributers', 'distributers.user_id', '=', 'users.id')->select('users.name')->where('distributers.id', $request->id)->first();
        $milkData->name = $user->name;
        return view('admin.distributer.snffat.single', compact('milkData'));
    }

    public function update(Request $request)
    {
        $milkData = Distributersnffat::find($request->id);
        $milkData->snf = $request->snf;
        $milkData->fat = $request->fat;
        $milkData->save();
        return response('ok');
    }
    public function delete(Request $request)
    {
        $milkData = Distributersnffat::find($request->id);
        $milkData->delete();
        return response('ok');
    }
}
