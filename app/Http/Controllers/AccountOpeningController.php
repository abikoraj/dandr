<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\NepaliDate;
use App\NepaliDateHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountOpeningController extends Controller
{
    public function index(Request $request)
    {
        $calculated = ['1.2', '1.4'];

        if ($request->getMethod() == "POST") {
            if (hasOpening($request->account_id)) {
                throw new \Exception('Account opening exists');
            }

            $date = getNepaliDate($request->date);
            $acc = DB::table('accounts')->where('id', $request->account_id)->first();
            $opening = pushAccountLedger($acc->id, $acc->type == 1 ? 2 : 1, $request->amount, 901, $date, 'Balance B/D');
            return view('admin.accounting.opening.single', compact('acc', 'opening'));
        } else {
            $fy = DB::table('fiscal_years')->where('name', env('fiscal_year'))->first();
            $openings = DB::table('account_ledgers')->where('fiscal_year_id', $fy->id)->where('identifier', '901')->get();
            $ids = $openings->pluck('account_id');
            $accounts = DB::table('accounts')
                ->where('fiscal_year_id', $fy->id)
                ->whereIn('type', [1, 2])
                ->whereNotIn('identifire', $calculated)
                ->orderBy('type')
                ->orderBy('identifire')
                ->get(['name', 'id', 'parent_id', 'identifire']);

            $parent_ids = $accounts->whereNotNull('parent_id')->pluck('parent_id');
            // dd($parent_ids);
            if ($parent_ids->count() > 0) {
                $accounts = $accounts->whereNotIn('id', $parent_ids);
            }
            
            $showAccounts = [];
            if ($ids->count() > 0) {
                $showAccounts = $accounts->whereNotIn('id', $ids);
            }else{
                $showAccounts=$accounts;
            }

            return view('admin.accounting.opening.index', compact('accounts', 'openings', 'fy', 'showAccounts'));
        }
    }
}
