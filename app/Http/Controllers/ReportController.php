<?php

namespace App\Http\Controllers;

use App\LedgerManage;
use App\Models\Advance;
use App\Models\Bill;
use App\Models\BillItem;
use App\Models\Center;
use App\Models\CreditNote;
use App\Models\DistributorPayment;
use App\Models\Distributer;
use App\Models\Distributorsell;
use App\Models\Employee;
use App\Models\Farmer;
use App\Models\FarmerReport;
use App\Models\Ledger;
use App\Models\Milkdata;
use App\Models\Sellitem;
use App\Models\Product;
use App\Models\Snffat;
use App\Models\SessionWatch;
use App\Models\FarmerSession;
use App\Models\EmployeeAdvance;
use App\Models\EmployeeReport;
use App\Models\Expense;
use App\Models\FiscalYear;
use App\Models\PosBill;
use App\Models\PosBillItem;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB as DB;

use function PHPSTORM_META\type;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function farmer(Request $request)
    {
        if ($request->getMethod() == "POST") {
            // dd($request->all());
            if ($request->s_number != null && $request->e_number != null) {
                $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('users.no', '>=', $request->s_number)->where('users.no', '<=', $request->e_number)->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();
            } else {
                $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();
            }
            $center = Center::find($request->center_id);
            $year = $request->year;
            $month = $request->month;
            $session = $request->session;
            $usetc = (env('usetc', 0) == 1) && ($center->tc > 0);
            $usecc = (env('usecc', 0) == 1) && ($center->cc > 0);
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $newsession = SessionWatch::where(['year' => $year, 'month' => $month, 'session' => $session, 'center_id' => $center->id])->count() == 0;
            // if(SessionWatch::where(['year'=>$year,'month'=>$month,'session'=>$session,'center_id'=>$center->id])->count()>0){
            //     $data=FarmerReport::where(['year'=>$year,'month'=>$month,'session'=>$session,'center_id'=>$center->id])->get();
            //     return view('admin.report.farmer.data1',compact('usecc','usetc','data','year','month','session','center'));

            // }else{

            $data = [];
            foreach ($farmers as $farmer) {
                if (FarmerReport::where(['year' => $year, 'month' => $month, 'session' => $session, 'user_id' => $farmer->id])->count() > 0) {
                    $_data = FarmerReport::where(['year' => $year, 'month' => $month, 'session' => $session, 'user_id' => $farmer->id])->first();

                    $farmer->old = true;
                } else {
                    $_data = LedgerManage::farmerReport($farmer->id, $range);
                }
                $farmer->milk = $_data->milk;
                $farmer->fpaid = $_data->fpaid;
                $farmer->fat = $_data->fat;
                $farmer->snf = $_data->snf;
                $farmer->rate = $_data->rate;
                $farmer->total = $_data->totalamount;
                $farmer->tc = $_data->tc;
                $farmer->cc = $_data->cc;
                $farmer->grandtotal = $_data->grandtotal;
                $farmer->bonus = $_data->bonus;
                $farmer->prevdue = $_data->prevdue;
                $farmer->due = $_data->due;
                $farmer->advance = $_data->advance;
                $farmer->prevbalance = $_data->prevbalance;
                $farmer->paidamount = $_data->paidamount;
                $farmer->nettotal = $_data->nettotal;
                $farmer->balance = $_data->balance;
                $farmer->advance = $_data->advance;
                array_push($data, $farmer);
            }
            return view('admin.report.farmer.data', compact('newsession', 'usetc', 'usecc', 'data', 'year', 'month', 'session', 'center'));
            // }
        } else {

            return view('admin.report.farmer.index');
        }
    }

    public function farmerSingleSession(Request $request)
    {
        $nextdate = NepaliDate::getNextDate($request->year, $request->month, $request->session);
        $lastdate = $lastdate = str_replace('-', '', $request->date);;
        $ledger = new LedgerManage($request->id);
        if (env('hasextra', 0) == 1) {
            $ledger->addLedger("Bonus", 1, $request->bonus, $lastdate, '124');
        }
        if ($request->grandtotal > 0) {
            $ledger->addLedger("Payment for milk (" . ($request->milk) . "l)", 2, $request->grandtotal, $lastdate, '108');
        }
        $farmerreport = new FarmerReport();
        $farmerreport->user_id = $request->id;
        $farmerreport->milk = $request->milk;
        $farmerreport->snf = $request->snf ?? 0;
        $farmerreport->fat = $request->fat ?? 0;
        $farmerreport->rate = $request->rate ?? 0;
        $farmerreport->total = $request->total ?? 0;
        $farmerreport->due = $request->due ?? 0;
        $farmerreport->bonus = $request->bonus ?? 0;
        $farmerreport->prevdue = $request->prevdue ?? 0;
        $farmerreport->advance = $request->advance ?? 0;
        $farmerreport->nettotal = $request->nettotal ?? 0;
        $farmerreport->balance = $request->balance ?? 0;
        $farmerreport->paidamount = $request->paidamount ?? 0;
        $farmerreport->prevbalance = $request->prevbalance ?? 0;
        $farmerreport->tc = $request->tc ?? 0;
        $farmerreport->cc = $request->cc ?? 0;
        $farmerreport->grandtotal = $request->grandtotal ?? $request->total;
        $farmerreport->year = $request->year;
        $farmerreport->month = $request->month;
        $farmerreport->session = $request->session;
        $farmerreport->fpaid = $request->fpaid;
        $farmer = Farmer::where('user_id', $request->id)->first();
        $farmerreport->center_id = $farmer->center_id;
        $farmerreport->save();
        return redirect()->back();
    }

    public function farmerSession(Request $request)
    {
        $nextdate = NepaliDate::getNextDate($request->year, $request->month, $request->session);
        $lastdate = str_replace('-', '', $request->date);

        foreach ($request->farmers as $farmer) {
            $data = json_decode($farmer);

            $ledger = new LedgerManage($data->id);
            $grandtotal = $data->grandtotal ?? 0;

            if ($data->grandtotal > 0) {
                $ledger->addLedger("Payment for milk (" . ($data->milk) . "l X " . ($data->rate ?? 0) . ")", 2, $data->grandtotal ?? 0, $lastdate, '108');
            }

            $farmerreport = new FarmerReport();
            $farmerreport->user_id = $data->id;
            $farmerreport->milk = $data->milk;
            $farmerreport->snf = $data->snf ?? 0;
            $farmerreport->fat = $data->fat ?? 0;
            $farmerreport->rate = $data->rate ?? 0;
            $farmerreport->total = $data->total ?? 0;
            $farmerreport->due = $data->due ?? 0;
            $farmerreport->prevdue = $data->prevdue ?? 0;
            $farmerreport->bonus = $data->bonus ?? 0;
            $farmerreport->advance = $data->advance ?? 0;
            $farmerreport->nettotal = $data->nettotal ?? 0;
            $farmerreport->balance = $data->balance ?? 0;
            $farmerreport->tc = $data->tc ?? 0;
            $farmerreport->fpaid = $data->fpaid ?? 0;
            $farmerreport->cc = $data->cc ?? 0;
            $farmerreport->grandtotal = $data->grandtotal ?? ($data->total ?? 0);
            $farmerreport->paidamount = $data->paidamount ?? 0;
            $farmerreport->prevbalance = $data->prevbalance;
            $farmerreport->year = $request->year;
            $farmerreport->month = $request->month;
            $farmerreport->session = $request->session;
            $farmerreport->center_id = $request->center_id;
            $farmerreport->save();
        }

        $sessionwatch = new SessionWatch();
        $sessionwatch->year = $request->year;
        $sessionwatch->month = $request->month;
        $sessionwatch->session = $request->session;
        $sessionwatch->center_id = $request->center_id;
        $sessionwatch->save();
        return redirect()->back();
    }

    public function milk(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];

            $milkdatas = MilkData::join('farmers', 'farmers.user_id', '=', 'milkdatas.user_id')
                ->join('centers', 'centers.id', '=', 'farmers.center_id')
                ->join('users', 'users.id', '=', 'milkdatas.user_id');


            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $milkdatas = $milkdatas->where('milkdatas.date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $milkdatas = $milkdatas->where('milkdatas.date', '>=', $range[1])->where('milkdatas.date', '<=', $range[2]);
            }

            $hascenter = false;
            if ($request->center_id != -1) {
                $hascenter = true;
                $milkdatas = $milkdatas->where('farmers.center_id', $request->center_id);
            }

            $datas = $milkdatas->select('milkdatas.m_amount', 'milkdatas.e_amount', 'milkdatas.user_id', 'milkdatas.date', 'farmers.center_id', 'users.name', 'users.no')->get();
            $data1 = $milkdatas->select(DB::raw('(sum(milkdatas.m_amount)+sum(milkdatas.e_amount)) as milk ,milkdatas.user_id ,users.name,users.no,farmers.center_id'))->groupBy('milkdatas.user_id', 'users.name', 'users.no', 'farmers.center_id')->get()->groupBy('center_id');



            return view('admin.report.milk.data', compact('data1'));
        } else {
            return view('admin.report.milk.index');
        }
    }

    public function sales(Request $request)
    {
        if ($request->getMethod() == "POST") {
            // dd($request->all());
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];
            $sellitem = Sellitem::join('farmers', 'farmers.user_id', '=', 'sellitems.user_id')
                ->join('users', 'users.id', '=', 'farmers.user_id')
                ->join('items', 'items.id', 'sellitems.item_id');

            $sellmilk = Distributorsell::join('distributers', 'distributers.id', '=', 'distributorsells.distributer_id')
                ->join('users', 'users.id', '=', 'distributers.user_id');

            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $sellitem = $sellitem->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $sellmilk = $sellmilk->where('distributorsells.date', '>=', $range[1])->where('distributorsells.date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $sellitem = $sellitem->where('sellitems.date', '=', $date);
                $sellmilk = $sellmilk->where('distributorsells.date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $sellitem = $sellitem->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $sellmilk = $sellmilk->where('distributorsells.date', '>=', $range[1])->where('distributorsells.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $sellitem = $sellitem->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $sellmilk = $sellmilk->where('distributorsells.date', '>=', $range[1])->where('distributorsells.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $sellitem = $sellitem->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $sellmilk = $sellmilk->where('distributorsells.date', '>=', $range[1])->where('distributorsells.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $sellitem = $sellitem->where('sellitems.date', '>=', $range[1])->where('sellitems.date', '<=', $range[2]);
                $sellmilk = $sellmilk->where('distributorsells.date', '>=', $range[1])->where('distributorsells.date', '<=', $range[2]);
            }

            if ($request->center_id != -1) {
                $sellitem = $sellitem->where('farmers.center_id', $request->center_id);
            }

            $data['sellitem'] = $sellitem->select('sellitems.date', 'sellitems.rate', 'sellitems.qty', 'sellitems.total', 'sellitems.due', 'users.name', 'items.title', 'users.no')->orderBy('sellitems.date', 'asc')->get();
            $data['sellitem1'] = $sellitem->select('sellitems.date', 'sellitems.rate', 'sellitems.qty', 'sellitems.total', 'sellitems.due', 'users.name', 'items.title', 'users.no')->orderBy('sellitems.date', 'asc')->get()->groupBy('title');
            // dd( $data['sellitem1']);
            $data['sellmilk'] = $sellmilk->select('distributorsells.*', 'users.name')->get();
            $data['sellmilk1'] = $sellmilk->select('distributorsells.*', 'users.name')->get()->groupBy('distributer_id');

            $maxdatas = [];
            foreach ($data['sellmilk1'] as $key => $d) {
                $dd = [];
                $dd['distributor'] = Distributer::find($key);
                $dt = $d->groupBy('product_id');
                $products = [];
                foreach ($dt as $key1 => $ddd) {
                    $product = [];
                    $product['product'] = Product::find($key1);
                    $product['qty'] = $ddd->sum('qty');
                    $product['rate'] = $ddd->avg('rate');
                    $product['total'] = $ddd->sum('total');
                    array_push($products, (object)$product);
                }
                $dd['products'] = $products;
                array_push($maxdatas, (object)$dd);
            }

            // dd($maxdatas);
            return view('admin.report.sales.data', compact('data', 'maxdatas'));
        } else {
            return view('admin.report.sales.index');
        }
    }


    // billing sale

    public function posSales(Request $request)
    {
        if ($request->isMethod('post')) {
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $type = $request->type;
            $range = [];
            $bill = PosBill::orderBy('id', 'asc');
            $bill_items = PosBillItem::join('pos_bills','pos_bills.id','=','pos_bill_items.pos_bill_id');
            $salesReturn_query=CreditNote::orderBy('id', 'asc');
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month,$request->session);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $bill = $bill->where('date', $date);
                $salesReturn_query = $salesReturn_query->where('date', $date);
                $bill_items = $bill_items->where('pos_bills.date', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);
                $range[2] = str_replace('-', '', $request->date2);
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            } elseif ($type == 6) {
                $fiscalYear=FiscalYear::find($request->fiscalYear);
                $range[1]=$fiscalYear->startdate;
                $range[2]=$fiscalYear->enddate;
                $bill = $bill->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $salesReturn_query = $salesReturn_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $bill_items = $bill_items->where('pos_bills.date', '>=', $range[1])->where('pos_bills.date', '<=', $range[2]);
            }

            $bills = $bill->orderBy('date', 'asc')->get();
            $billitems = $bill_items->orderBy('pos_bills.date', 'asc')->select(
                DB::raw('pos_bill_items.*,pos_bills.bill_no')
            )->get();

            $saleReturn=$salesReturn_query->select('id','bill_no','date','total')->get();
            $ddd=$billitems->groupBy('item_id');
            $billItemDatas=[];

            foreach ($ddd as $key=>$b) {
                $billItemData=[];
                $billItemData['item_id']=$key;
                $billItemData['item_name']=$b->first()->name;
                $billItemData['value']=[];
                $ssd=$b->groupBy('rate');
                foreach ($ssd as $key1 => $b1) {
                    $sd=[];
                    $sd['rate']=$key1;
                    $sd['qty']=$b1->sum('qty');
                    $sd['amount']=$b1->sum('amount');
                    $sd['discount']=$b1->sum('discount');
                    $sd['taxable']=$b1->sum('taxable');
                    $sd['tax']=$b1->sum('tax');
                    $sd['total']=$b1->sum('total');
                    // $sd['bi']=$b1;
                    array_push($billItemData['value'],$sd);
                }
                array_push($billItemDatas, $billItemData);
            }
            // dd($billItemDatas);
            $groupData = json_encode(ReportManager::makeGroup($type,$request),JSON_NUMERIC_CHECK );
            // dd($groupData);
            return view('admin.report.billingsale.data', compact('bills', 'billItemDatas','type','groupData','saleReturn'));
        } else {
            return view('admin.report.billingsale.index');
        }
    }




    // distributer

    public function distributor(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $elements = [];
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $range = [];
            $data = [];
            $date = -1;
            $distributers = Distributer::join('users', 'users.id', '=', 'distributers.user_id')->select(DB::raw('distributers.id,users.id as user_id, users.name'))->get();
            foreach ($distributers as $key => $distributer) {
                $data = Ledger::where('user_id', $distributer->user_id);
                if ($type == 0) {
                    $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 1) {
                    $date = $date = str_replace('-', '', $request->date1);
                    $range[1] = $date;
                    $data = $data->where('date', '=', $date);
                } elseif ($type == 2) {
                    $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 3) {
                    $range = NepaliDate::getDateMonth($request->year, $request->month);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 4) {
                    $range = NepaliDate::getDateYear($request->year);
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                } elseif ($type == 5) {
                    $range[1] = str_replace('-', '', $request->date1);;
                    $range[2] = str_replace('-', '', $request->date2);;
                    $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                }
                $element=$distributer->toArray();
                $data1=clone $data;
                $data2=clone $data;
                $element['milk']=$data->where('identifire','132')->sum('amount');
                $element['total']=$data1->where('identifire','103')->sum('amount');
                $element['paid']=$data2->where('identifire','114')->sum('amount');

                $element['due'] = 0;
                $element['advance'] = 0;

                $element['prevadvance'] = 0;
                $element['prevdue'] = 0;


                $opening = 0;
                $balance = 0;
                $prev = 0;

                if (env('acc_system', 'old') == 'old') {
                    $prev = Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 2)->sum('amount') -
                    Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 1)->sum('amount');
                } else {
                    $prev = Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 2)->sum('amount') -
                    Ledger::where('date', '<', $range[1])->where('user_id', $element['user_id'])->where('type', 1)->sum('amount');
                }

                if (env('acc_system', 'old') == 'old') {
                    $element['opening'] = Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])
                        ->where('type', 2)->where('identifire', 119)->sum('amount') -
                        Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])->where('type', 1)->where('identifire', 119)->sum('amount');
                } else {
                    $element['opening'] = Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])
                        ->where('type', 1)->where('identifire', 119)->sum('amount') -
                        Ledger::where('date', '>=', $range[1])
                        ->where('user_id', $element['user_id'])->where('type', 2)->where('identifire', 119)->sum('amount');
                }

                $prev +=  $element['opening'];
                if ($prev > 0) {
                    $element['prevadvance'] = $prev;
                    $element['prevdue'] = 0;
                } else {
                    $element['prevadvance'] = 0;
                    $element['prevdue'] = (-1) * $prev;
                }

                $element['total']=$element['total']+$element['milk'];
                $balance = $prev - $element['total'] + $element['paid'];

                if ($balance > 0) {
                    $element['advance'] = $balance;
                    $element['due'] = 0;
                } else {
                    $element['advance'] = 0;
                    $element['due'] = (-1) * $balance;
                }

                array_push($elements, $element);
            }

            // dd($elements);
            return view('admin.report.distributor.data', compact('elements'));
        } else {
            return view('admin.report.distributor.index');
        }
    }

    public function employee(Request $request)
    {
        if ($request->getMethod() == "POST") {
            // dd($request->all());
            $range = NepaliDate::getDateMonth($request->year, $request->month);
            $year = $request->year;
            $month = $request->month;
            $employees = Employee::all();
            $data = [];
            foreach ($employees as $employee) {
                if (EmployeeReport::where('employee_id', $employee->id)->where('year', $request->year)->where('month', $request->month)->count() > 0) {
                    $report = EmployeeReport::where('employee_id', $employee->id)->where('year', $request->year)->where('month', $request->month)->first();
                    $employee->prevbalance = $report->prevbalance;
                    $employee->advance = $report->advance;
                    $employee->salary = $report->salary;
                    $employee->old = true;
                } else {
                    $employee->prevbalance = Ledger::where('user_id', $employee->user_id)->where('identifire', '101')->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount');
                    $employee->advance = EmployeeAdvance::where('employee_id', $employee->id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount');
                    $employee->old = false;
                }
                array_push($data, $employee);

                // dd($data);
            }
            // $advance=EmployeeAdvance::where
            return view('admin.report.employee.data', compact('data', 'year', 'month'));
        } else {
            return view('admin.report.employee.index');
        }
    }

    public function employeeSession(Request $request)
    {
        foreach ($request->employees as $employee) {
            $report = new EmployeeReport();
            $report->employee_id = $employee->id;
            $report->prebalance = $employee->prevbalance;
            $report->advance = $employee->advance;
            $report->salary = $employee->salary;
            $report->save();
        }
        return redirect()->back();
    }



    public function credit()
    {
        $farmercredit = \App\Models\User::where('role', 1)->where('amount', '>', 0)->where('amounttype', 1)->get();
        $distributorcredit = \App\Models\User::where('role', 2)->where('amount', '>', 0)->where('amounttype', 1)->get();
        return view('admin.report.credit.index', compact('farmercredit', 'distributorcredit'));
    }


    public function expense(Request $request)
    {
        if ($request->isMethod('post')) {
            $type = $request->type;
            $range = [];
            $data = [];
            $data = Expense::orderBy('id', 'desc');

            if ($type == 0) {
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $data = $data->where('date', '=', $date);
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $data = $data->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            }
            if ($request->category_id == null) {
                $data = $data->get();
            }
            $hascat = false;
            if ($request->category_id != -1) {
                $hascat = true;
                $data = $data->where('expcategory_id', $request->category_id)->get();
            } else {
                $data = $data->get();
            }
            return view('admin.report.expense.data', compact('data'));
        } else {
            return view('admin.report.expense.index');
        }
    }

    public function bonus(Request $request){
        if($request->getMethod()=="POST"){
            $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();

            $year1=$request->year1;
            $year2=$request->year2;
            $month1=$request->month1;
            $month2=$request->month2;
            $f_data=[];
            $timer=1;

            foreach ($farmers as $key => $farmer) {
                $timer=1;
                $semi=true;
                $_year1=$year1;
                $_year2=$year2;
                $_month1=$month1;
                $_month2=$month2;
                $data=[];
                $session=1;
                $sum=0;
                while ($semi) {
                    $_bonus=FarmerReport::where([
                        ['year',$_year1],
                        ['month',$_month1],
                        ['session',$session],
                        ['user_id',$farmer->id]
                        ])->sum('bonus');
                    array_push($data,[$_year1,$_month1,$session,$_bonus]);
                    $sum+=$_bonus;
                    $session+=1;
                    $timer+=1;
                    if($session>2){
                        $_month1+=1;
                        $session=1;
                    }
                    if($_month1>12){
                        $_year1+=1;
                        $_month1=1;
                    }
                    $semi=(($_year1*10000)+($_month1)*100)<=(($_year2*10000)+($_month2)*100);
                }
                $farmer->data=$data;
                $farmer->sum=$sum;
                array_push($f_data,$farmer);
            }
            // dd($f_data);
            return view('admin.report.bonus.data',['farmers'=>$f_data,'detailed'=>$request->detailed,'times'=>$timer]);


        }else{
            return view('admin.report.bonus.index');
        }
    }

    public function bonus1(Request $request){
        if($request->getMethod()=="POST"){
            $farmers = Farmer::join('users', 'users.id', '=', 'farmers.user_id')->where('farmers.center_id', $request->center_id)->select('users.id', 'users.name', 'users.no', 'farmers.center_id')->orderBy('users.no', 'asc')->get();

            $year1=$request->year1;
            $year2=$request->year2;
            $month1=$request->month1;
            $month2=$request->month2;
            $f_data=[];
            $timer=1;

            foreach ($farmers as $key => $farmer) {
                $timer=1;
                $semi=true;
                $_year1=$year1;
                $_year2=$year2;
                $_month1=$month1;
                $_month2=$month2;
                $data=[];
                $session=1;
                $sum=0;
                while ($semi) {
                    $range=$range = NepaliDate::getDate($_year1,$_month1,$session);
                    $_bonus=$this->getBonus($farmer->id,$range);
                    // array_push($data,[$_year1,$_month1,$session,$_bonus]);
                    $sum+=$_bonus;
                    $session+=1;
                    $timer+=1;
                    if($session>2){
                        $_month1+=1;
                        $session=1;
                    }
                    if($_month1>12){
                        $_year1+=1;
                        $_month1=1;
                    }
                    $semi=(($_year1*10000)+($_month1)*100)<=(($_year2*10000)+($_month2)*100);
                }
                // $farmer->data=$data;
                $farmer->sum=$sum;
                array_push($f_data,$farmer);
            }
            // dd($f_data);
            return view('admin.report.bonus.data',['farmers'=>$f_data,'detailed'=>$request->detailed,'times'=>$timer]);


        }else{
            return view('admin.report.bonus.index');
        }
    }

    public function getBonus($user_id, $range){
        $farmer1 = User::find($user_id);


        $snfAvg = truncate_decimals(Snffat::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->avg('snf'), 2);
        $fatAvg = truncate_decimals(Snffat::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->avg('fat'), 2);

        $center = Center::where('id', $farmer1->farmer()->center_id)->first();

        $fatAmount = ($fatAvg * $center->fat_rate);
        $snfAmount = ($snfAvg * $center->snf_rate);

        $farmer1->snf = $snfAvg;
        $farmer1->fat = $fatAvg;
        if ($farmer1->farmer()->userate == 1) {

            $farmer1->rate = $farmer1->farmer()->rate;
        } else {

            $farmer1->rate = truncate_decimals($fatAmount + $snfAmount);
        }

        $farmer1->milk = Milkdata::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('e_amount') + Milkdata::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('m_amount');

        $farmer1->totalamount = truncate_decimals(($farmer1->rate * $farmer1->milk), 2);

        $farmer1->tc = 0;
        $farmer1->cc = 0;


        if ($farmer1->farmer()->usetc == 1 && $farmer1->totalamount > 0) {
            $farmer1->tc = truncate_decimals((($center->tc * ($snfAvg + $fatAvg) / 100) * $farmer1->milk), 2);
        }
        if ($farmer1->farmer()->usecc == 1 && $farmer1->totalamount > 0) {
            $farmer1->cc = truncate_decimals($center->cc * $farmer1->milk, 2);
        }


        $farmer1->grandtotal = (int)($farmer1->totalamount + $farmer1->tc + $farmer1->cc);
        $farmer1->bonus = 0;
        if (env('hasextra', 0) == 1) {
            $farmer1->bonus = (int)($farmer1->grandtotal * $center->bonus / 100);
            return $farmer1->bonus;
        }else{
            return 0;
        }
    }
}
