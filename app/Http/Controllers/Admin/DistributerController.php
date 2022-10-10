<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LedgerManage;
use App\Models\Distributer;
use App\Models\DistributerMilk;
use App\Models\DistributerMilkReport;
use App\Models\Distributerreq;
use App\Models\Distributersnffat;
use App\Models\DistributorPayment;
use App\Models\Distributorsell;
use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
use App\NepaliDateHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributerController extends Controller
{


    public function index()
    {
        return view('admin.distributer.index');
    }

    public function add(Request $request)
    {
        $user = new User();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $dis = new Distributer();
        $dis->user_id = $user->id;
        $dis->rate = $request->rate ?? 0;
        $dis->amount = $request->amount ?? 0;
        $dis->credit_days = $request->credit_days;
        $dis->credit_limit = $request->credit_limit;
        $dis->snf_rate = $request->snf_rate ?? 0;
        $dis->fat_rate = $request->fat_rate ?? 0;
        $dis->added_rate = $request->added_rate ?? 0;
        $dis->is_fixed = $request->is_fixed ?? 0;
        $dis->fixed_rate = $request->fixed_rate ?? 0;
        $dis->save();

        $newuser=User::join('distributers', 'distributers.user_id', '=', 'users.id')->select('users.*', DB::raw('distributers.id as dis_id,distributers.snf_rate,distributers.fat_rate,distributers.added_rate,distributers.is_fixed,distributers.fixed_rate,distributers.credit_days,distributers.credit_limit'))->where('users.id', $user->id)->first();
        return view('admin.distributer.single', ['user'=>$newuser]);
    }

    public function list()
    {
        $distributer = User::join('distributers', 'distributers.user_id', '=', 'users.id')->select('users.*', DB::raw('distributers.id as dis_id,distributers.snf_rate,distributers.fat_rate,distributers.added_rate,distributers.is_fixed,distributers.fixed_rate,distributers.credit_days,distributers.credit_limit'))->orderBy('distributers.id', 'asc')->get();
        // dd($distributer);
        return view('admin.distributer.list', compact('distributer'));
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->phone = $request->phone;
        $user->name = $request->name;
        $user->address = $request->address;
        $user->role = 2;
        $user->password = bcrypt($request->phone);
        $user->save();
        $dis = Distributer::where('user_id', $user->id)->first();
        $dis->rate = $request->rate ?? 0;
        $dis->amount = $request->amount ?? 0;
        $dis->credit_days = $request->credit_days;
        $dis->credit_limit = $request->credit_limit;
        $dis->snf_rate = $request->snf_rate ?? 0;
        $dis->fat_rate = $request->fat_rate ?? 0;
        $dis->added_rate = $request->added_rate ?? 0;
        $dis->is_fixed = $request->is_fixed ?? 0;
        $dis->fixed_rate = $request->fixed_rate ?? 0;
        $dis->save();
        $user=User::join('distributers', 'distributers.user_id', '=', 'users.id')->select('users.*', DB::raw('distributers.id as dis_id,distributers.snf_rate,distributers.fat_rate,distributers.added_rate,distributers.is_fixed,distributers.fixed_rate,distributers.credit_days,distributers.credit_limit'))->where('users.id', $request->id)->first();
        return view('admin.distributer.single', compact('user'));
    }

    public function delete(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        $user->delete();
    }


    //XXX distributer Detail
    public function distributerDetail($id)
    {
        $user = User::where('id', $id)->where('role', 2)->first();
        return view('admin.distributer.detail', compact('user'));
    }

    public function distributerDetailLoad(Request $request)
    {
        // $range=NepaliDate::getDate($r->year,$r->month,$r->session);
        // $ledger = Ledger::where('user_id',$r->user_id)->where('date','>=',$range[1])->where('date','<=',$range[2])->orderBy('ledgers.id','asc')->get();
        // $d = Distributer::where('user_id',$r->user_id)->first();
        // $sell = Distributorsell::where('distributer_id',$d->id)->where('date','>=',$range[1])->where('date','<=',$range[2])->get();
        $year = $request->year;
        $month = $request->month;
        $week = $request->week;
        $session = $request->session;
        $type = $request->type;
        $range = [];
        $data = [];
        $date = 1;
        $title = "";
        $distributer = Distributer::where('user_id', $request->user_id)->first();
        $ledger = Ledger::where('user_id', $request->user_id)->orderBy('date', 'asc')->orderBy('id', 'asc');
        if ($type == 0) {
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            $title .= "<span class='mx-2'>Session:" . $session . "</span>";
        } elseif ($type == 1) {
            $date = $date = str_replace('-', '', $request->date1);
            $ledger = $ledger->where('date', '=', $date);
            $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
        } elseif ($type == 2) {
            $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            $title .= "<span class='mx-2'>Week:" . $week . "</span>";
        } elseif ($type == 3) {
            $range = NepaliDate::getDateMonth($request->year, $request->month);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
            $title .= "<span class='mx-2'>Month:" . $month . "</span>";
        } elseif ($type == 4) {
            $range = NepaliDate::getDateYear($request->year);
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year:" . $year . "</span>";
        } elseif ($type == 5) {
            $range[1] = str_replace('-', '', $request->date1);;
            $range[2] = str_replace('-', '', $request->date2);;
            $ledger = $ledger->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>from:" . $request->date1 . "</span>";
            $title .= "<span class='mx-2'>To:" . $request->date2 . "</span>";
        }
        // dd($ledger->toSql(),$ledger->getBindings());
        $base = 0;
        $prev = 0;
        $closing = 0;
        $arr = [];
        $ledgers = $ledger->orderBy('date', 'asc')->get();
        $cr=0;$dr=0;
        foreach ($ledgers as $key => $l) {

            if ($l->type == 1) {
                $base -= $l->amount;
            } else {
                $base += $l->amount;
            }
            if ($l->date < $range[1]) {
                $prev = $base;
            }
            if ($l->date >= $range[1] && $l->date <= $range[2]) {
                $l->amt = $base;
                $closing = $base;
                array_push($arr, $l);
            }
        }

        
        $user = User::where('id', $request->user_id)->first();

        $milkData = [];
        if ($type == 0 && env('dis_snffat',0)==1) {
            $milkData['milk'] = DistributerMilk::where('distributer_id', $distributer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();
            $milkData['snffat'] = Distributersnffat::where('distributer_id', $distributer->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->get();
            $milkData['closingDate']=NepaliDate::getDateSessionLast($request->year, $request->month,$request->session);
            $milkData['report']=DistributerMilkReport::where([
                'year'=>$request->year,'month'=> $request->month,'session'=>$request->session,'distributer_id'=> $distributer->id
            ])->first();
            $milkData['req']=(object)$request->all();
        }

        return view('admin.distributer.data', compact('distributer', 'arr', 'type', 'user', 'title', 'prev', 'closing', 'milkData'));
    }



    public function opening()
    {
        return view('admin.distributer.balance.index');
    }

    public function loadLedger(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $ledgers = Ledger::where('identifire', '119')->get();
        // $ledgers = Ledger::where('date', $date)->where('identifire', '119')->get();
        return view('admin.distributer.balance.list', compact('ledgers'));
    }
    public function ledger(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $dis = Distributer::where('id', $request->id)->first();
        $ledger = new LedgerManage($dis->user_id);
        $d = $ledger->addLedger("Opening Balance", $request->type, $request->amount, $date, '119');
        return view('admin.distributer.balance.single', compact('d'));
    }

    public function updateLedger(Request $request)
    {
        // return response("multiple Ledger Already added Cannot be Deleted for distributor",500);

        $oldledger = Ledger::find($request->id);
        $user = User::find($oldledger->user_id);
        if (Ledger::where('user_id', $oldledger->user_id)->count() > 1) {
            return response("multiple Ledger Already added Cannot be Deleted", 500);
        }
        $oldledger->delete();
        $user->amount = 0;
        $user->save();
    }


    // distributer request
    public function distributerRequest()
    {
        $disReqs = Distributerreq::latest()->get();
        return view('admin.distributer.request', compact('disReqs'));
    }

    public function distributerRequestChangeStatus($id)
    {
        $disReqs = Distributerreq::where('id', $id)->first();
        $disReqs->status = 1;
        $disReqs->save();
        return redirect()->back();
    }


    public function creditList(Request $request)
    {
        if ($request->getMethod() == "POST") {
        } else {
            $credits = User::where('role', 2)->where('amount', '>', 0)->where('amounttype', 1)->get();

            $data = [];
            $s1 = false;
            $s2 = false;
            foreach ($credits as $key => $credit) {
                $date = null;
                $credit->dis = $credit->distributer();
                $canprocess = false;
                $latestPayment = DistributorPayment::where('user_id', $credit->id)->orderBy('date', 'desc')->first();
                if ($latestPayment == null) {
                    $latestLedger = Ledger::where('user_id', $credit->id)->orderBy('id', 'desc')->first();
                    if ($latestLedger != null) {
                        $date = $latestLedger->date;
                    }
                } else {
                    $date = $latestPayment->date;
                }
                if ($canprocess) {
                    $s1 = true;
                } else {
                    $dateHelper = NepaliDateHelper::withDate($date);
                    $d = $dateHelper->_eng_date;
                    $engdate = Carbon::createFromDate($d['year'], $d['month'], $d['date']);
                    $compareDate = Carbon::now()->subDays($credit->dis->credit_days);
                    // echo $engdate ."<br>";
                    // echo $compareDate."<br>";
                    if ($engdate->lessThan($compareDate)) {
                        $s1 = true;
                    }
                }

                $s2 = $credit->dis->credit_limit < $credit->amount;
                // echo ($s1?1:0) ." - s1 <br>";
                // echo ($s2?1:0) ." - s2 <br>";
                if ($s1 || $s2) {
                    $credit->date = $date;
                    array_push($data, $credit);
                }
            }
            return view('admin.distributer.credit.index', compact('data'));
        }
    }

    public function changePassword(Request $request,User $user)
    {
       
        if($request->getMethod()=="POST"){

        }else{
            return view('admin.distributer.changepass',compact('user'));
        }
    }
}
