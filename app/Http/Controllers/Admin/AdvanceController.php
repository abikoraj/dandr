<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Advance;
use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
use App\PaymentManager;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
    public function index()
    {
        return view('admin.farmer.advance.index');
    }

    public function add(Request $request)
    {

        $date = str_replace('-', '', $request->date);
        $adv = new Advance();
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('users.no', $request->no)->where('farmers.center_id', $request->center_id)->select('users.*', 'farmers.center_id')->first();
        // $user = User::where('no',$request->no)->first();
        $d = new NepaliDate($date);
        if (!$d->isPrevClosed($user->id)) {
            return response('notOk');
        }
        if ($user == null) {
            return response("Farmer Not Found", 400);
        } else {
            if ($user->no == null) {
                return response("Farmer Not Found", 500);
            }
        }
        $adv->user_id = $user->id;
        $adv->amount = $request->amount;
        $adv->date = $date;
        $adv->save();
        $ledger = new LedgerManage($user->id);
        if (env('acc_system', 'old') == 'old') {
            $ledger->addLedger('Advance to Farmer', 1, $request->amount, $date, '104', $adv->id);
        } else {
            $ledger->addLedger('Advance to Farmer', 2, $request->amount, $date, '104', $adv->id);
        }
        new PaymentManager($request, $adv->id, 104, 'By '.$user->name. ' A/C');
        return view('admin.farmer.advance.single', compact('adv'));
    }

    public function list(Request $r)
    {
        $date = str_replace('-', '', $r->date);
        $advs = User::join('farmers', 'users.id', '=', 'farmers.user_id')
            ->join('advances', 'advances.user_id', '=', 'farmers.user_id')
            ->where('farmers.center_id', $r->center_id)
            ->where('advances.date', $date)
            // ->where('advance')
            ->select('farmers.user_id', 'users.no', 'advances.*', 'users.name')->get();
        // dd($user);
        // dd($user);
        // $advs = Advance::where('date',$date)->whereIn('user_id',$user)->get();
        return view('admin.farmer.advance.list', compact('advs'));
    }


    // public function update(Request $request){
    //     $adv = Advance::find($request->id);
    // }


    function listByDate(Request $r)
    {
        $date = str_replace('-', '', $r->date);
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $r->center_id)->select('farmers.user_id')->get();
        // dd($user);
        $advs = Advance::where('date', $date)->whereIn('user_id', $user)->get();
        return view('admin.farmer.advance.list', compact('advs'));
        // return response()->json($adv);
        // dd($date);
    }


    public function delete(Request $request)
    {
        $adv = Advance::where('id', $request->id)->first();

        $ledger = Ledger::where(['user_id' => $adv->user_id, 'identifire' => '104', 'foreign_key' => $request->id])->first();
        // if($ledger!=null){
        //     LedgerManage::delLedger([$ledger]);
        // }
        $ledger->delete();
        $adv->delete();
        PaymentManager::remove($request->id, 104);

        return response('Advance Deleted Sucessfully');
    }
    public function update(Request $request)
    {
        // dd($request->all());
        $adv = Advance::where('id', $request->id)->first();
        $adv->amount = $request->amount;
        $ledger = Ledger::where(['user_id' => $adv->user_id, 'identifire' => '104', 'foreign_key' => $request->id])->first();
        $ledger->amount = $request->amount;
        $ledger->save();
        $adv->save();

        PaymentManager::update( $request);
        return view('admin.farmer.advance.single', compact('adv'));
    }
}
