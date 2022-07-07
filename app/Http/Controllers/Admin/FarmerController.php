<?php

/**
 * Author : Chhatraman Shrestha, Gopal Ghimire
 * Company: Need Technosoft
 * Contact: needtechnosoft@gmail.com,9800916365,9819356415
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Advance;
use App\Models\Center;
use App\Models\Farmer;
use App\Models\Farmerpayment;
use App\Models\FarmerReport;
use App\Models\Ledger;
use App\Models\Milkdata;
use App\Models\Sellitem;
use App\Models\Snffat;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FarmerController extends Controller
{
    public function index()
    {
        return view('admin.farmer.index');
    }


    public function listFarmerByCenter(Request $request)
    {
        $farmers = User::join('farmers', 'farmers.user_id', '=', 'users.id')->where('farmers.center_id', $request->center)->select('users.id', 'users.name', 'users.phone', 'users.address', 'farmers.center_id', 'farmers.usecc', 'farmers.usetc', 'farmers.userate', 'farmers.rate', 'farmers.no')->orderBy('farmers.no', 'asc')->get();
        return view('admin.farmer.list', ['farmers' => $farmers]);
    }

    public function minlistFarmerByCenter(Request $request)
    {
        $farmers = DB::table('users')->join('farmers', 'farmers.user_id', '=', 'users.id')->where('farmers.center_id', $request->center)->select('farmers.no', 'users.name')->orderBy('users.no', 'asc')->get();
        return view('admin.farmer.minlist', ['farmers' => $farmers]);
    }

    public function farmerDetail($id)
    {
        $user = User::where('id', $id)->first();
        // dd($user);
        return view('admin.farmer.detail', compact('user'));
    }



    // public function loadSessionData(Request $r)
    // {
    //     $range = NepaliDate::getDate($r->year, $r->month, $r->session);
    //     $data = $r->all();
    //     $farmer1 = User::where('id', $r->user_id)->first();
    //     $center = Center::where('id', $farmer1->farmer()->center_id)->first();

    //     $farmer1->old = FarmerReport::where(['year' => $r->year, 'month' => $r->month, 'session' => $r->session, 'user_id' => $r->user_id])->count() > 0;

    //     $sellitem = Sellitem::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();
    //     $milkData = Milkdata::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();
    //     $snfFats = Snffat::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();

    //     $snfAvg = truncate_decimals(Snffat::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->avg('snf'), 2);
    //     $fatAvg = truncate_decimals(Snffat::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->avg('fat'), 2);


    //     $fatAmount = ($fatAvg * $center->fat_rate);
    //     $snfAmount = ($snfAvg * $center->snf_rate);

    //     $farmer1->snfavg = $snfAvg;
    //     $farmer1->fatavg = $fatAvg;
    //     if ($farmer1->farmer()->userate == 1) {

    //         $farmer1->milkrate = $farmer1->farmer()->rate;
    //     } else {

    //         $farmer1->milkrate = truncate_decimals($fatAmount + $snfAmount);
    //     }

    //     $farmer1->milkamount = Milkdata::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('e_amount') + Milkdata::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('m_amount');

    //     $farmer1->total = truncate_decimals(($farmer1->milkrate * $farmer1->milkamount), 2);

    //     $farmer1->tc = 0;
    //     $farmer1->cc = 0;


    //     if ($farmer1->farmer()->usetc == 1 && $farmer1->total > 0) {
    //         $farmer1->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer1->milkamount), 2);
    //     }
    //     if ($farmer1->farmer()->usecc == 1 && $farmer1->total > 0) {
    //         $farmer1->cc = truncate_decimals($center->cc * $farmer1->milkamount, 2);
    //     }


    //     $farmer1->fpaid = (Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '106')->sum('amount') + Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '107')->sum('amount'));

    //     $farmer1->grandtotal = (int)($farmer1->total + $farmer1->tc + $farmer1->cc);

    //     $farmer1->bonus = 0;
    //     if (env('hasextra', 0) == 1) {
    //         $farmer1->bonus = (int)($farmer1->grandtotal * $center->bonus / 100);
    //     }


    //     $farmer1->due = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])
    //     ->where('date', '<=', $range[2])->
    //     where('identifire', '103')->sum('amount');;

    //     $previousMonth = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->
    //     where('date', '<=', $range[2])->
    //     where('identifire', '101')->sum('amount');
    //     // $previousMonth1 = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 1)->sum('amount');
    //     // $previousBalance = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 2)->sum('amount');

    //     $previousMonth1 = 0;
    //     $previousBalance = 0;
    //     $base = 0;
    //     $prev = 0;
    //     $closing = 0;
    //     $arr = [];
    //     $ledgers = Ledger::where('user_id', $r->user_id)->where('date', '<=', $range[2])->where('identifire', '!=', 109)->where('identifire', '!=', 120)->orderBy('date', 'asc')->orderBy('id', 'asc')->get();
    //     foreach ($ledgers as $key => $l) {

    //         if ($l->type == 1) {
    //             $base -= $l->amount;
    //         } else {
    //             $base += $l->amount;
    //         }
    //         if ($l->date < $range[1]) {
    //             $prev = $base;
    //         }
    //         if ($l->date >= $range[1] && $l->date <= $range[2]) {
    //             $l->amt = $base;
    //             $closing = $base;
    //             array_push($arr, $l);
    //         }
    //     }
    //     $farmer1->ledger = $arr;
    //     if ($prev < 0) {
    //         $previousBalance = -1 * $prev;
    //     } else {
    //         $previousMonth1 = $prev;
    //     }

    //     $farmer1->advance = (float)(Advance::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount'));
    //     $farmer1->prevdue = (float)$previousMonth + (float)$previousMonth1;
    //     $farmer1->prevbalance = (float)$previousBalance;
    //     $farmer1->paidamount = (float)Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '121')->where('type', 1)->sum('amount');
    //     $balance = $farmer1->grandtotal + $farmer1->balance - $farmer1->prevdue - $farmer1->advance - $farmer1->due - $farmer1->paidamount + $farmer1->prevbalance - $farmer1->bonus + $farmer1->fpaid;

    //     $farmer1->balance = 0;
    //     $farmer1->nettotal = 0;
    //     if ($balance < 0) {
    //         $farmer1->balance = (-1) * $balance;
    //     }
    //     if ($balance > 0) {
    //         $farmer1->nettotal = $balance;
    //     }

    //     // $farmer1->ledger = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->orderBy('id', 'asc')->get();
    //     $milk_rate = FarmerReport::where(['user_id' => $r->user_id, 'year' => $r->year, 'month' => $r->month, 'session' => $r->session])->first();
    //     // dd($milk_rate);
    //     // dd(compact('snfFats','milkData','data','center','farmer1'));
    //     $closingDate=NepaliDate::getDateSessionLast($r->year, $r->month, $r->session);
    //     return view('admin.farmer.detail.index', compact('snfFats', 'milkData', 'data', 'center', 'farmer1', 'milk_rate','prev','closing','closingDate'));
    // }

    public function loadSessionData(Request $r)
    {
        $range = NepaliDate::getDate($r->year, $r->month, $r->session);
        $data = $r->all();
        $farmer1 = User::where('id', $r->user_id)->first();
        $center = Center::where('id', $farmer1->farmer()->center_id)->first();

        $farmer1->old = FarmerReport::where(['year' => $r->year, 'month' => $r->month, 'session' => $r->session, 'user_id' => $r->user_id])->count() > 0;

        $milkData = Milkdata::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();
        $snfFats = Snffat::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();

        $snfAvg = truncate_decimals($snfFats->avg('snf'), 2);
        $fatAvg = truncate_decimals($snfFats->avg('fat'), 2);


        $fatAmount = ($fatAvg * $center->fat_rate);
        $snfAmount = ($snfAvg * $center->snf_rate);

        $farmer1->snfavg = $snfAvg;
        $farmer1->fatavg = $fatAvg;

        if ($farmer1->farmer()->userate == 1) {

            $farmer1->milkrate = $farmer1->farmer()->rate;
        } else {

            $farmer1->milkrate = truncate_decimals($fatAmount + $snfAmount);
        }

        $farmer1->milkamount = $milkData->sum('e_amount') + $milkData->sum('m_amount');
        $farmer1->total = truncate_decimals(($farmer1->milkrate * $farmer1->milkamount), 2);
        $farmer1->tc = 0;
        $farmer1->cc = 0;


        if ($farmer1->farmer()->usetc == 1 && $farmer1->total > 0) {
            $farmer1->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer1->milkamount), 2);
        }
        if ($farmer1->farmer()->usecc == 1 && $farmer1->total > 0) {
            $farmer1->cc = truncate_decimals($center->cc * $farmer1->milkamount, 2);
        }

        $farmer1->fpaid = ledgerSum($r->user_id, '106', $range) + ledgerSum($r->user_id, '107', $range);

        $farmer1->grandtotal = (int)($farmer1->total + $farmer1->tc + $farmer1->cc);
        $farmer1->bonus = 0;

        if (env('hasextra', 0) == 1) {
            $farmer1->bonus = (int)($farmer1->grandtotal * $center->bonus / 100);
        }

        $farmer1->purchase = ledgerSum($r->user_id, '103', $range);

        // $previousMonth1 = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 1)->sum('amount');
        // $previousBalance = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 2)->sum('amount');

        $previousMonth1 = 0;
        $previousBalance = 0;

        $closing = 0;
        $arr = [];
        $prev = ledgerPrev($r->user_id, $range[1], 2) - ledgerPrev($r->user_id, $range[1], 1);
        $previousMonth = ledgerSum($r->user_id, '101', $range, 2) - ledgerSum($r->user_id, '101', $range, 1)
        + ledgerSum($r->user_id, '102', $range, 2) - ledgerSum($r->user_id, '102', $range, 1);
        $base = $prev;

        $ledgers = Ledger::where('user_id', $r->user_id)
            ->where('date', '>=', $range[1])
            ->where('date', '<=', $range[2])
            ->where('identifire', '!=', 109)->
            where('identifire', '!=', 120)->
            orderBy('date', 'asc')->
            orderBy('id', 'asc')->get();

        // array_push($arr,(object)[
        //     'title'=>'Previous Balance	'
        // ]);
        foreach ($ledgers as $key => $l) {
            if ($l->type == 1) {
                $base -= $l->amount;
            } else {
                $base += $l->amount;
            }
            $l->amt = $base;
            $closing = $base;
            array_push($arr, $l);
        }
        $farmer1->ledger = $arr;
        $prevtotal=$prev+$previousMonth;

        // dd($prev,$previousMonth,$prevtotal);
        if ($prevtotal < 0) {
            $previousBalance = -1 * $prevtotal;
        } else {
            $previousMonth1 = $prevtotal;
        }


        $farmer1->advance = (float)(Advance::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount'));
        $farmer1->prevdue = (float)$previousMonth1;
        $farmer1->prevbalance = (float)$previousBalance;
        $farmer1->paidamount = (float)Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '121')->where('type', 1)->sum('amount');
        $balance = $farmer1->grandtotal + $farmer1->balance - $farmer1->prevdue - $farmer1->advance - $farmer1->purchase - $farmer1->paidamount + $farmer1->prevbalance - $farmer1->bonus + $farmer1->fpaid;

        $farmer1->balance = 0;
        $farmer1->nettotal = 0;
        if ($balance < 0) {
            $farmer1->balance = (-1) * $balance;
        }
        if ($balance > 0) {
            $farmer1->nettotal = $balance;
        }

        // $farmer1->ledger = Ledger::where('user_id', $r->user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->orderBy('id', 'asc')->get();
        $milk_rate = FarmerReport::where(['user_id' => $r->user_id, 'year' => $r->year, 'month' => $r->month, 'session' => $r->session])->first();
        // dd($milk_rate);
        // dd(compact('snfFats','milkData','data','center','farmer1'));
        $closingDate = NepaliDate::getDateSessionLast($r->year, $r->month, $r->session);
        return view('admin.farmer.detail.index', compact('snfFats', 'milkData', 'data', 'center', 'farmer1', 'milk_rate', 'prev', 'closing', 'closingDate'));
    }



    public function addFarmer(Request $request)
    {

        if ($request->filled('farmer_no')) {
            $max = $request->farmer_no;
        } else {
            $max = (DB::table('users')
                ->join('farmers', 'farmers.user_id', '=', 'users.id')
                ->where('farmers.center_id', $request->center_id)
                ->max('farmers.no') ?? 0) + 1;
        }
        $user = new User();
        $user->phone = $request->phone ?? "9800000000";
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 1;
        $user->password = bcrypt(12345);
        $user->no = $max;
        $user->save();

        $id = $user->id;
        $farmer = new Farmer();
        $farmer->user_id = $user->id;
        $farmer->center_id = $request->center_id;
        $farmer->usecc = $request->usecc ?? 0;
        $farmer->usetc = $request->usetc ?? 0;
        $farmer->userate = $request->userate ?? 0;
        $farmer->rate = $request->f_rate ?? 0;
        $farmer->no = $max;
        $farmer->save();


        $user->usecc = $farmer->usecc;
        $user->usetc = $farmer->usetc;
        $user->userate = $farmer->userate;
        $user->rate = $farmer->rate;
        return view('admin.farmer.single', compact('user'));
    }

    public function updateFarmer(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if ($request->filled('phone')) {

            $user->phone = $request->phone;
        }
        $user->no = $request->no;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->save();

        $farmer = Farmer::where('user_id', $request->id)->first();
        $farmer->usecc = $request->usecc ?? 0;
        $farmer->usetc = $request->usetc ?? 0;
        $farmer->userate = $request->userate ?? 0;
        $farmer->rate = $request->rate;
        $farmer->no = $request->no;

        $farmer->save();

        $user->usecc = $farmer->usecc;
        $user->usetc = $farmer->usetc;
        $user->userate = $farmer->userate;
        $user->rate = $farmer->rate;

        return view('admin.farmer.single', compact('user'));
    }

    public function deleteFarmer($id)
    {
        $user = User::where('id', $id)->first();
        $user->delete();
        return response()->json('Delete successfully !');
    }


    // due payment controller
    public function due()
    {
        return view('admin.farmer.due.index');
    }

    public function dueLoad(Request $request)
    {
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('users.no', $request->no)->where('farmers.center_id', $request->center_id)->select('users.*', 'farmers.center_id')->first();
        $user->balance = Ledger::where('user_id', $user->id)->where('type', 2)->sum('amount') - Ledger::where('user_id', $user->id)->where('type', 1)->sum('amount');
        $user->payments = db::table('farmerpayments')->where('user_id', $user->id)->orderBy('date')->get();
        // dd($balance);
        return view('admin.farmer.due.due', compact('user'));
    }

    public function paymentDelete(Request $request)
    {
        DB::delete('delete from farmerpayments where id = ?', [$request->id]);
        DB::delete('delete from ledgers where foreign_key = ? and identifire=107', [$request->id]);
        return response('ok');
    }

    public function paymentSave(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('users.no', $request->no)->where('farmers.center_id', $request->center_id)->select('users.*', 'farmers.center_id')->first();
        $farmerPay = new Farmerpayment();
        $farmerPay->amount = $request->pay;
        $farmerPay->date = $date;
        $farmerPay->payment_detail = $request->detail;
        $farmerPay->user_id = $user->id;
        $farmerPay->save();

        $ledger = new LedgerManage($user->id);
        if (env('acc_system', 'old') == "old") {

            $ledger->addLedger('Paid by farmer amount', 2, $request->pay, $date, '107', $farmerPay->id);
        } else {
            $ledger->addLedger('Paid by farmer amount', 1, $request->pay, $date, '107', $farmerPay->id);
        }
        return response('Payment Added Sucessfully');
    }

    public function addDueList(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $date = str_replace('-', '', $request->date);

            $ledgers = User::join('farmers', 'users.id', '=', 'farmers.user_id')
                ->join('ledgers', 'ledgers.user_id', '=', 'users.id')
                ->where('farmers.center_id', $request->center)
                ->where('ledgers.date', $date)
                ->where('ledgers.identifire', 101)
                ->select('ledgers.id', 'ledgers.amount', 'ledgers.type', 'users.no', 'users.name', 'farmers.center_id')->get();
            // dd($ledgers,$request->all());
            return view('admin.farmer.due.list.list', compact('ledgers'));
        } else {
            return view('admin.farmer.due.list.index');
        }
    }


    public function addDue(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $user = User::join('farmers', 'users.id', '=', 'farmers.user_id')->where('users.no', $request->id)->where('farmers.center_id', $request->center_id)->select('users.*', 'farmers.center_id')->first();
        $ledger = new LedgerManage($user->id);
        $l = $ledger->addLedger('Opening Balance', $request->type, $request->amount, $date, '101');
        $l->name = $user->name;
        $l->no = $user->no;
        return view('admin.farmer.due.list.single', ['ledger' => $l]);
    }
}
