<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FiscalYear;
use App\Models\Ledger;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    public function index()
    {
        return view('admin.accounting.index');
    }

    public function final(Request $request)
    {
        if ($request->getMethod() == "POST") {

            $showDetail = $request->filled('detail');
            $range = [];
            $type = $request->type;
            $fy = FiscalYear::where('id', $request->fy)->first();
            if ($fy == null) {
                throw new \Exception('Fiscal Year Not Found');
            }
            $startYear = (int) ($fy->startdate / 10000);
            $endYear = (int) ($fy->enddate / 10000);

            if ($type == 1) {
                $range[1] = $fy->startdate;
                $range[2] = $fy->enddate;
            } else if ($type == 2) {
                $q = $request->quater;
                $y = $q < 4 ? $startYear : $endYear;
                $range = [
                    NepaliDate::getDateMonth($y, $q < 4 ? ($q * 3) + 1 : 1)[1],
                    NepaliDate::getDateMonth($y, $q < 4 ? (($q + 1) * 3) : 3)[2]
                ];
            } else if ($type == 3) {
                if ($request->month < 4) {
                    $range = NepaliDate::getDateMonth($endYear, $request->month);
                } else {
                    $range = NepaliDate::getDateMonth($startYear, $request->month);
                }
            }

            $openingdata = DB::selectOne("select id,opening from stocks where date>={$range[1]} and date <={$range[2]} and opening is not null order by date,id asc limit 1");
            $closingdata = DB::selectOne("select id,closing from stocks where date>={$range[1]} and date <={$range[2]} and closing is not null order by date,id desc limit 1");
            $opening=$openingdata==null?0:$openingdata->opening;
            $closing=$closingdata==null?0:$closingdata->closing;
            // dd($opening,$closing);
            $queries = [
                'milk' => " select sum(amount) from ledgers where identifire=108 ",
                'supplier' => "select sum(total) from supplierbills where canceled=0 ",
                'purchaseExpense' => "select sum(be.amount) from bill_expenses be join supplierbills b on be.supplierbill_id=b.id where canceled=0 ",
                'counter1' => "select sum(grandtotal) from pos_bills where is_canceled=0 ",
                'counter2' => "select sum(grandtotal) from bills where id>0 ",
                'farmer1' => "select  sum(total) from sellitems where id>0 ",
                'distributer1' => "select  total from distributorsells where id>0 ",
            ];
            $temp = [];
            foreach ($queries as $key => $query) {
                array_push($temp, "( " . $query . " and (date>={$range[1]} and date<={$range[2]} )) as {$key}");
            }


            $query = "select " . implode(",", $temp);


            $trading = DB::selectOne(
                $query
            );

            $farmerdata=DB::selectOne("select  sum(total) as total from sellitems 
            where (date>={$range[1]} and date<={$range[2]} )
             and user_id in (select u.id from users u join farmers f on u.id=f.user_id) ");
            $trading->farmer=$farmerdata!=null?$farmerdata->total:0;

            $distributerdata=DB::selectOne("select  sum(total) as total from sellitems 
            where (date>={$range[1]} and date<={$range[2]} )
             and user_id in (select u.id from users u join distributers f on u.id=f.user_id) ");
            $trading->distributer=$distributerdata!=null?$distributerdata->total:0;
            // dd($trading);
            $trading->counter = $trading->counter1 + $trading->counter2;
            $trading->sales = $trading->counter + $trading->farmer + $trading->distributer;
            $trading->cr = $trading->sales + $closing;
            $trading->purchase = $trading->milk + $trading->supplier;
            $trading->dr = $opening + $trading->purchase + $trading->purchaseExpense;
            $trading->status = ($trading->cr == $trading->dr) ? 'none' : ($trading->cr > $trading->dr ? 'profit' : 'loss');
            if ($trading->status == 'profit') {
                $trading->profit = $trading->cr - $trading->dr;
                $trading->loss = null;
                $trading->total = $trading->cr;
            } else if ($trading->status == 'loss') {
                $trading->loss = $trading->dr - $trading->cr;
                $trading->profit = null;
                $trading->total = $trading->dr;
            } else {
                $trading->loss = null;
                $trading->profit = null;
                $trading->total = $trading->cr;
            }



            $plac = (object)[];
            $plac->dr = 0;
            $plac->cr = 0;
            $plac->salary = DB::selectOne('select sum(amount) as amount from ledgers where identifire=129 and (date>=? and date<=?)', [$range[1], $range[2]])->amount;
            $plac->expenses = DB::select("select e.amount,etype.name,e.expcategory_id from
            (select expcategory_id,sum(amount) as amount from expenses  where date>={$range[1]} and date <={$range[2]} group by expcategory_id) e
            join expcategories etype on etype.id=e.expcategory_id");
            $plac->incomes = DB::select("select ei.amount,eic.name from
            (select sum(amount) as amount,extra_income_category_id from extra_incomes  where date>={$range[1]} and date <={$range[2]} group by extra_income_category_id)  ei
            join extra_income_categories eic on eic.id=ei.extra_income_category_id");
            if ($trading->status == 'loss') {
                $plac->dr += $trading->loss;
            } else if ($trading->status == "profit") {
                $plac->cr += $trading->profit;
            }

            $plac->dr += $plac->salary;

            foreach ($plac->expenses as $key => $expense) {
                $plac->dr += $expense->amount;
            }
            foreach ($plac->incomes as $key => $income) {
                $plac->cr += $income->amount;
            }

            $plac->status = ($plac->cr == $plac->dr) ? 'none' : ($plac->cr > $plac->dr ? 'profit' : 'loss');
            if ($plac->status == 'profit') {
                $plac->profit = $plac->cr - $plac->dr;
                $plac->loss = null;
                $plac->total = $plac->cr;
            } else if ($plac->status == 'loss') {
                $plac->loss = $plac->dr - $plac->cr;
                $plac->profit = null;
                $plac->total = $plac->dr;
            } else {
                $plac->loss = null;
                $plac->profit = null;
                $plac->total = $plac->cr;
            }


            $bs=(object)[];
            // dd($trading,$plac);

            if($type==1){
                $parties=[
                    "Farmer"=>'farmers',
                    "Employee"=>'employees',
                    "Distributor"=>'distributers',
                    "Customer"=>'customers',
                    "Supplier"=>'suppliers'
                ];

                $partyDatas=[];
                foreach ($parties as $key => $party) {
                    $partyDatas[$key]=$this->getPayableReceivable("select (dr-cr) as amount,user_id from
                    (
                    select
                    ifnull(( select sum(amount) from ledgers where user_id=emp.user_id and type=1 and date>={$range[1]} and date <={$range[2]}),0) as cr,
                    ifnull(( select sum(amount) from ledgers where user_id=emp.user_id and type=2 and date>={$range[1]} and date <={$range[2]}),0) as dr,
                    emp.user_id
                     from
                    (select user_id from users u join {$party} e on e.user_id=u.id)  as emp
                    ) as l where cr-dr<>0");
                }
                // dd($partyDatas);
                $bs->extras=["liablilty"=>[],"asset"=>[]];

                $bs->parties=(object)$parties;
                $bs->partyDatas=(object)$partyDatas;
                $bs->receivable=[];
                $bs->payable=[];
                $bs->receivableAmount=0;
                $bs->payableAmount=0;
                foreach ($partyDatas as $key => $partyData) {
                    if($partyData['receivable']>0){


                            $bs->receivableAmount+=$partyData['receivable'];
                            $title="Receivable from ".$key;

                            if($key=="Farmer"){
                                $title="Farmer Due";
                            }else if($key=="Employee"){
                                $title="Employee Advance";
                            }else if($key=="Distributor"){
                                $title="Distributor Due";
                            }
                            array_push($bs->receivable,[
                                "title"=>$title,
                                'amount'=>$partyData['receivable']
                            ]);

                    }
                    if($partyData['payable']>0){
                        $bs->payableAmount+=$partyData['payable'];
                        $title="Payable To ".$key;
                        if($key=="Employee"){
                            $title="Salary Payable";
                        }else if($key=="Supplier"){
                            $title="Supplier Due";
                        }
                        array_push($bs->payable,[
                            "title"=>$title,
                            'amount'=>$partyData['payable']
                        ]);
                    }
                }

                $accounts=DB::table('accounts')->where('fiscal_year_id',$fy->id)->whereNull('parent_id')->get()->groupBy('type');
                $bs->assets=$accounts[1];
                $bs->liabilities=$accounts[2];
                foreach ($bs->assets as $key => $acc) {
                    if($acc->identifire=='1.2'){
                        $acc->amount=DB::table('banks')->where('account_id',$acc->id)->sum('balance');
                        $acc->banks=DB::table('banks')->where('account_id',$acc->id)->get(['name','balance']);
                    }else if($acc->identifire=='1.4'){
                        $fixedAssets=DB::table('fixed_assets')->where('account_id',$acc->id)->get();
                        $acc->amount=0;
                        $acc->depreciation=0;
                        $acc->assets=DB::select("select name ,amount from(
                            select sum(amount) as amount,fixed_asset_category_id from fixed_assets where account_id=? group by fixed_asset_category_id
                            ) fd join fixed_asset_categories cat on fd.fixed_asset_category_id =cat.id ", [$acc->id]);
                        foreach ($fixedAssets as $key => $asset) {
                            $acc->amount+=$asset->amount;
                            $acc->depreciation+= $asset->full_amount*$asset->depreciation/100;
                        }

                    }
                }
                foreach ($bs->liabilities as $key => $acc) {
                    if($acc->identifire=='2.1'){
                        $acc->status=$plac->status;
                        $acc->totalCapital=$acc->amount;
                        if ($plac->status == 'profit') {
                            $acc->profit=$plac->profit;
                            $acc->totalCapital+=$acc->profit;
                        }else if($plac->status=='loss'){
                            $acc->loss=$plac->loss;
                            $acc->totalCapital-=$acc->loss;
                        }
                    }
                }
            }

            return view('admin.accounting.final.index', compact('type','trading', 'opening', 'closing', 'showDetail', 'range', 'plac','bs'));
        } else {
            $fys = DB::table('fiscal_years')->get(['id', 'name', 'startdate', 'enddate']);
            return view('admin.accounting.final.result', compact('fys'));
        }
    }

    public function accounts(Request $request)
    {
        if($request->getMethod()=="POST"){

            if($request->parent_id==0){
                $accounts = Account::whereNull('parent_id')->where('fiscal_year_id',$request->fiscal_year_id)->get()->groupBy('type');
            }else{
                $accounts = Account::where('parent_id',$request->parent_id)->get()->groupBy('type');
            }
            return view('admin.accounting.accounts.list',compact('accounts'));
        }else{
            $fys=DB::table('fiscal_years')->get(['id','name']);
            $selectedfy=getFiscalYear();
            return view('admin.accounting.accounts', compact('fys','selectedfy'));
        }
    }

    public function accountsAdd(Request $request, $type, $parent_id)
    {
        if ($request->getMethod() == "POST") {
            $parent = Account::where('id', $parent_id)->first();

            if ($parent_id == 0) {
                $fy = getFiscalYear();
            } else {
                $fy = DB::table('fiscal_years')->where('id', $parent->fiscal_year_id)->first();
            }

            if ($fy == null) {
                throw new \Exception("Cannot Find Current Fiscal Year");
            }

            $identifire = ($parent_id != 0 ? $parent->identifire : $type) . "." . $request->identifire;
            if (DB::table('accounts')->where('identifire', $identifire)->where('fiscal_year_id', $fy->id)->count() > 0) {
                throw new \Exception("Account with identifire {$identifire} already exists");
            }

            $account = new Account();
            $account->identifire = $identifire;
            $account->parent_id = $parent_id == 0 ? null : $parent_id;
            $account->name = $request->name;
            $account->type = $type;
            $account->amount = $request->amount;
            $account->fiscal_year_id = $fy->id;
            $account->save();
            if($account->parent_id!=null){
                $this->manageAccounts($account);
            }
        } else {
            $parent = Account::where('id', $parent_id)->first();
            return view('admin.accounting.accounts.add', compact('type', 'parent_id', 'parent'));
        }
    }

    public function accountsEdit(Request $request,$id)
    {
        $calculated=['1.2','1.4'];
        $nameEdit=['1.1','1.2','1.3','1.4','2.1','2.2','2.3'];
        $account=Account::where('id',$id)->first();
        if($request->getMethod()=="POST"){
            if (in_array($account->identifire,$nameEdit) && in_array($account->identifire,$calculated)){
                throw new \Exception("Cannot Update Account.");
            }
            else{
                if (!in_array($account->identifire,$nameEdit) ){
                    $account->name=$request->name;
                }
                if (!in_array($account->identifire,$calculated) ){
                    $account->amount=$request->amount;
                }
                $account->save();
                if($account->parent_id!=null){
                    $this->manageAccounts($account);
                }
                return response()->json(['status'=>true]);
            }
        }else{
            return view('admin.accounting.accounts.edit',compact('account','calculated','nameEdit'));
        }
    }

    public function subAccounts(Request $request,$id){
        $account=Account::where('id',$id)->first();
        $parent_id=$account->parent_id;
        $parents=[];
        while($parent_id!=null){
            $acc=Account::where('id',$parent_id)->first();
            $parent_id=$acc->parent_id;
            array_push($parents,$acc);
        }
        array_reverse($parents);
        $accounts=DB::table('accounts')->where('parent_id',$id)->get();
        // dd($accounts);
        return view('admin.accounting.accounts.subaccounts',compact('accounts','parents','account'));
    }

    private function manageAccounts($account){
        $parent_id=$account->parent_id;
        while ($parent_id!=null) {
            $parentacc=Account::where('id',$parent_id)->first(['parent_id','id','amount']);
            $parentacc->amount=DB::table('accounts')->where('parent_id',$parent_id)->sum('amount');
            $parent_id=$parentacc->parent_id;
            $parentacc->save();
        }

    }

    private function getPayableReceivable($query)
    {
        // dd($query);
        $receivable=0;
        $payable=0;
        $rows=DB::select($query);
        for ($i=0; $i < count($rows); $i++) {
            $row=$rows[$i];
            if($row->amount<0){
                $payable+=(-1)* $row->amount;
            }else{
                $receivable+=$row->amount;
            }
        }
        return compact('receivable','payable');
    }
}
