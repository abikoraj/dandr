<?php

use App\Models\Center;
use App\Models\CenterStock;
use App\Models\Item;
use App\Models\Setting;
use App\NepaliDate;
use App\NepaliDateHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

define('farmer', 1);
define('distributer', 2);
define('employee', 3);
define('supplier', 4);
define('customer', 5);

$xxx_per = "data";

class fy
{
    public static $value;
}

function lCanDelete($identifire)
{
    return !in_array($identifire, [106]);
}
function _nepalidate($date)
{
    if ($date == null) {
        return '--';
    }
    $year = (int)($date / 10000);
    $date = $date % 10000;
    $month = (int)($date / 100);
    $day = (int)($date % 100);
    return $year . "-" . ($month < 10 ? "0" . $month : $month) . "-" . ($day < 10 ? "0" . $day : $day);
}

function sessionType(int $s)
{
    $st = ['Any', 'Morning', 'Evening'];
    return $st[$s];
}

function counterStatus(int $s)
{
    $st = ['', 'Requested', 'Running', 'Closed'];
    return $st[$s];
}

function toNepaliDate($date)
{
    if ($date == null) {
        return null;
    } else {
        $rem = str_replace('-', '', $date);
        if (is_numeric($rem)) {

            return intval($rem);
        } else {
            return null;
        }
    }
}

function truncate_decimals($number, $decimals = 2)
{
    // $factor = pow(10, $decimals);
    // $val = intval($number * $factor) / $factor;
    // return $val;
    $factor = pow(10, $decimals);

    $mul = strval($number * $factor);
    $pos = strpos($mul, '.');
    if ($pos != false) {
        $mul = substr($mul, 0, $pos);
    }
    $data = intval($mul);
    $val = ($data) / $factor;

    return $val;
}

function rupee($amount)
{
    $fmt = new NumberFormatter($locale = 'en_IN', NumberFormatter::DECIMAL);
    return $fmt->format($amount);
}

function userDir($id)
{
    $data = sprintf("%'.09d", $id);
    $arr = str_split($data, 3);
    return implode('/', $arr);
}
function getOffers()
{
    return [
        'Flat Discount',
        'percentage',
        'Buy And get Free'
    ];
}
function numberTowords(float $amount)
{
    $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
    // Check if there is any number after decimal
    $amt_hundred = null;
    $count_length = strlen($num);
    $x = 0;
    $string = array();
    $change_words = array(
        0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
    );
    $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($x < $count_length) {
        $get_divider = ($x == 2) ? 10 : 100;
        $amount = floor($num % $get_divider);
        $num = floor($num / $get_divider);
        $x += $get_divider == 10 ? 1 : 2;
        if ($amount) {
            $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
            $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
            $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . '
         ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . '
         ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
        } else $string[] = null;
    }
    $implode_to_Rupees = implode('', array_reverse($string));
    $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . "
   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
    return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}

function coll_sum($collection, $column)
{
    return $collection->sum($column);
}

function backup_path(): string
{
    return  public_path('backup');
}

function has_per($per, $list): bool
{
    return $list->where('code', $per)->count() > 0;
}

function isSuper()
{
    return Auth::check() ? ((Auth::user()->phone) == env('authphone', '')) : false;
}

function auth_has_per($per)
{
    try {
        $user = Auth::user();
        if ($user->phone == env('authphone', "9852059171")) {
            return true;
        } else {
            return Config::get('per.per')->where('enable', 1)->where('code', $per)->count() > 0;
        }
        //code...
    } catch (\Throwable $th) {
        //throw $th;
        return false;
    }
}

function xxx_per_func()
{
    Config::set('per.per', ['database']);
}

function roleToWord($key)
{
    return str_replace('_', ' ', $key);
}

function getSetting($key, $direct = false)
{
    $s = DB::table('settings')->where('key', $key)->select('value')->first();
    return $direct ? ($s != null ? $s->value : null) : ($s != null ? json_decode($s->value) : null);
}

function setSetting($key, $value, $direct = false)
{
    $s = Setting::where('key', $key)->first();
    if ($s == null) {
        $s = new Setting();
        $s->key = $key;
    }
    if ($direct) {
        $s->value = $value;
    } else {

        $s->value = json_encode($value);
    }
    $s->save();
    return $s;
}
// function

function maintainStockCenter($item_id, $qty, $center_id, $dir = 'in')
{
    if (CenterStock::where('item_id', $item_id)->where('center_id', $center_id)->count() > 0) {
        if ($dir == 'in') {
            DB::update('update center_stocks set amount = amount+? where item_id = ? and center_id=?', [$qty, $item_id, $center_id]);
        } else {
            DB::update('update center_stocks set amount = amount-? where item_id = ? and center_id=?', [$qty, $item_id, $center_id]);
        }
    } else {
        $item = Item::where('id', $item_id)->select('id', 'stock', 'wholesale', 'sell_price')->first();
        $centerStock = new CenterStock();
        $centerStock->item_id = $item_id;
        $centerStock->center_id = $center_id;
        $centerStock->wholesale = $item->wholesale;
        $centerStock->rate = $item->sell_price;
        if ($dir == 'in') {

            $centerStock->amount = $qty;
        } else {
            $centerStock->amount = -1 * $qty;
        }
        $centerStock->save();
    }
}
function maintainStock($item_id, $qty, $center_id = null, $dir = 'in')
{



    $item = Item::where('id', $item_id)->select('id', 'stock', 'wholesale', 'sell_price', 'trackstock')->first();
    if ($item->trackstock == 0) {
        return;
    }
    if ($dir == 'in') {
        $item->stock += $qty;
    } else {
        $item->stock -= $qty;
    }
    $item->save();

    if (env('multi_stock', false)) {


        if ($center_id == null) {
            $center = Center::where('id', env('maincenter'))->first();
            if ($center == null) {
                return;
            }
            $center_id = $center->id;
        }
        if (CenterStock::where('item_id', $item_id)->where('center_id', $center_id)->count() > 0) {
            if ($dir == 'in') {
                DB::update('update center_stocks set amount = amount+? where item_id = ? and center_id=?', [$qty, $item_id, $center_id]);
            } else {
                DB::update('update center_stocks set amount = amount-? where item_id = ? and center_id=?', [$qty, $item_id, $center_id]);
            }
        } else {
            $centerStock = new CenterStock();
            $centerStock->item_id = $item_id;
            $centerStock->center_id = $center_id;
            $centerStock->wholesale = $item->wholesale;
            $centerStock->rate = $item->sell_price;
            if ($dir == 'in') {
                $centerStock->amount = $qty;
            } else {
                $centerStock->amount = -1 * $qty;
            }
            $centerStock->save();
        }
    }
}

function isMainCenter($id)
{
    return env('maincenter', -1) == $id;
}

function currentStock()
{
    return DB::selectOne('select round(sum(stock * ( case when sell_price = 0   then cost_price when sell_price<cost_price then cost_price else sell_price end)),2) as sum from items');
}

function rangeSelector($request, $query, $column = 'date')
{
    $year = $request->year;
    $month = $request->month;
    $week = $request->week;
    $session = $request->session;
    $type = $request->type;
    $range = [];
    $data = [];
    $date = 1;
    if ($type == 0) {
        $range = NepaliDate::getDate($year, $month, $session);
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 1) {
        $date = $date = str_replace('-', '', $request->date1);
        $query = $query->where($column, '=', $date);
    } elseif ($type == 2) {
        $range = NepaliDate::getDateWeek($year, $month, $week);
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 3) {
        $range = NepaliDate::getDateMonth($year, $month);
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 4) {
        $range = NepaliDate::getDateYear($year);
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 5) {
        $range[1] = str_replace('-', '', $request->date1);;
        $range[2] = str_replace('-', '', $request->date2);;
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } else if ($type == 6) {
        $fy = DB::selectOne('select startdate,enddate from fiscal_years where id=?', [$request->fiscalyear]);
        // dd($fy);
        $query = $query->where($column, '>=', $fy->startdate)->where($column, '<=', $fy->enddate);
    }
    return $query;
}

function rangeSelectorEng($request, $query, $column = 'date')
{
    $year = $request->year;
    $month = $request->month;
    $week = $request->week;
    $session = $request->session;
    $type = $request->type;
    $range = [];
    $data = [];
    $date = 1;

    if ($type == 0) {
        $range = NepaliDate::getDate($year, $month, $session);
        $range=NepaliDateHelper::engRange($range);
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 1) {
        $date = $date = str_replace('-', '', $request->date1);
        $date=NepaliDateHelper::engDate($date);
        $query = $query->where($column, '=', $date);
    } elseif ($type == 2) {
        $range = NepaliDate::getDateWeek($year, $month, $week);
        $range=NepaliDateHelper::engRange($range);

        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 3) {
        $range = NepaliDate::getDateMonth($year, $month);
        $range=NepaliDateHelper::engRange($range);

        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 4) {
        $range = NepaliDate::getDateYear($year);
        $range=NepaliDateHelper::engRange($range);

        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } elseif ($type == 5) {
        $range[1] = str_replace('-', '', $request->date1);;
        $range[2] = str_replace('-', '', $request->date2);;
        $range=NepaliDateHelper::engRange($range);

        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    } else if ($type == 6) {
        $fy = DB::selectOne('select startdate,enddate from fiscal_years where id=?', [$request->fiscalyear]);
        $range[1]= $fy->startdate;
        $range[2]= $fy->enddate;
        $range=NepaliDateHelper::engRange($range);
        $query = $query->where($column, '>=', $range[1])->where($column, '<=', $range[2]);
    }
    return $query;
}
function renderEmpList()
{
    $emps = DB::select('select u.name from employees e join users u on e.user_id=u.id');
    $html = "<datalist id='emp_datalist'>";
    foreach ($emps as $key => $emp) {
        $html .= "<option value='{$emp->name}'>{$emp->name}</option>";
    }
    $html .= "</datalist>";
    return $html;
}

function renderCenters($center_id = null, $blank = false)
{
    $centers = DB::select('select id,name from centers');
    $html = $blank ? "<option></option>" : "";

    foreach ($centers as $key => $center) {
        if ($center_id == $center->id) {
            $html .= "<option selected value='{$center->id}'>{$center->name}</option>";
        } else {
            $html .= "<option value='{$center->id}'>{$center->name}</option>";
        }
    }
    return $html;
}


function getFiscalYear()
{
    if (fy::$value == null) {
        $name = env('fiscal_year', null);
        // dd($name);
        if ($name != null) {
            fy::$value = DB::table('fiscal_years')->where('name', $name)->first();
            // dd($fy);
        } else {
            $nepaliDateHelper = new NepaliDateHelper();
            $date = Carbon::now();
            $currentdate = $nepaliDateHelper->eng_to_nepInt($date->year, $date->month, $date->day);
            fy::$value = DB::table('fiscal_years')->where('startdate', '>=', $currentdate)->where('enddate', '<=', $currentdate)->first();
        }
    }
    return fy::$value;
}

function subAccounts($id)
{
    return DB::table('accounts')->where('parent_id', $id)->get(['name', 'amount']);
}

function assetCategory($id)
{
    if ($id == null) {
        return null;
    } else {
        return DB::table('fixed_asset_categories')->where('id', $id)->first(['name'])->name;
    }
}


function getBanks()
{
    try {
        //code...
        $fy = getFiscalYear();
        $banks = DB::select("select name,id,account_id from banks
            where account_id in (select id from accounts
            where parent_id= (select id from accounts where identifire='1.2' and fiscal_year_id={$fy->id} limit 1)
        )");
        // dd($banks);
        return $banks;
    } catch (\Throwable $th) {
        //throw $th;
        return [];
    }
}

function getCashAcc()
{
    $fy = getFiscalYear();
}

function getFiscalYears()
{
    return DB::table('fiscal_years')->get(['id', 'name']);
}


function hasPay()
{
    return env('xpay', false);
}

function ledgerPrev($id, $date, $type = null, $identifire = null)
{
    $query = DB::table('ledgers')->where('user_id', $id)->where('date', '<', $date);
    if ($type != null) {
        $query = $query->where('type', $type);
    }
    if ($identifire != null) {
        $query = $query->where('identifire', $identifire);
    }
    return $query->sum('amount') ?? 0;
}
function ledgerSum($id, $identifire, $range = null, $type = null)
{
    $query = DB::table('ledgers')->where('user_id', $id)->where('identifire', $identifire);
    if ($range != null) {
        $query = $query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
    }
    if ($type != null) {
        $query = $query->where('type', $type);
    }
    return $query->sum('amount') ?? 0;
}

function randomChance($min = 0, $max = 1, $no = 1)
{
    return mt_rand($min, $max) == $no;
}


function modalMenuData()
{
    return DB::selectOne('select
    (select count(*) from farmers) as farmers,
    (select count(*) from suppliers) as suppliers,
    (select count(*) from distributers) as distributers,
    (select count(*) from manufactured_products ) as products,
    (select count(*) from manufacture_processes ) as processes,
    (select count(*) from centers ) as centers
    ');
}

function nepaliToday()
{
    $n = new NepaliDateHelper();
    return $n->today();
}


function nepaliMonthName(int $i)
{
    $_n = [
        "",
        "Baisakh",
        "Jestha",
        "Ashar",
        "Shrawan",
        "Bhadra",
        "Ashoj",
        "Kartik",
        "Mangsir",
        "Poush",
        "Magh",
        "Falgun",
        "Chaitra",
    ];
    return $_n[$i];
}

function makeFive($num)
{
    try {

        $mod = $num % 5;
        // return $mod;
        if ($mod == 0) {
            return $num;
        } else {
            return ($num - $mod) + ($mod == 4 ? 5 : 0);
        }
    } catch (\Throwable $th) {
        return 0;
    }
}


function scriptSafe($data)
{
    return str_replace('script', 'div', $data);
}

function getNepaliDate($di)
{
    if (NepaliDate::isWrongDate($di)) {
        throw new \Exception("Wrong Date Format", 1);
    }
    $dateParts = explode('-', $di);
    return ($dateParts[0] * 10000) + ($dateParts[1] * 100) + ($dateParts[2] * 1);

    // return str_replace('-','',$di);
}

function getFiscalYearMonth($fy)
{
    $monthArray = [];
    $startYear = (int)($fy->startdate / 10000);
    $month = 4;
    for ($i = 0; $i < 12; $i++) {
        $monthSTR = $month < 10 ? ('0' . $month) : $month;
        array_push($monthArray, [$startYear * 100 + $month, "{$startYear}-{$monthSTR}", [$startYear, $month]]);
        $month += 1;
        if ($month > 12) {
            $month = 1;
            $startYear += 1;
        }
    }
    return $monthArray;
}


function prependZero($num)
{
    return $num < 10 ? ('0' . $num) : $num;
}

function canDelOpening($user_id){
    return DB::table('ledgers')->where('user_id',$user_id)->count()<=1;
}


include_once 'custom_acounting.php';
include_once 'custom_ext1.php';
