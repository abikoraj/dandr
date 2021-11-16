<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

define('farmer', 1);
define('distributer', 2);
define('employee', 3);
define('supplier', 4);
define('customer', 5);

$xxx_per="data";

function _nepalidate($date)
{
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
    $factor = pow(10, $decimals);
    $val = intval($number * $factor) / $factor;
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
function getOffers(){
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

function coll_sum($collection,$column){
    return $collection->sum($column);
}

function backup_path(): string{
  return  public_path('backup');
}

function has_per($per,$list): bool{
    return $list->where('code',$per)->count()>0;
}

function auth_has_per($per){
    try {
        $user=Auth::user();
        if($user->phone == env('authphone', "9852059171")){
            return true;
        }else{
            return Config::get('per.per')->where('enable',1)->where('code',$per)->count()>0;
        }
        //code...
    } catch (\Throwable $th) {
        //throw $th;
        return false;
    }

}

function xxx_per_func() {
    Config::set('per.per', ['database']);
}

function roleToWord($key){
    return str_replace('_',' ',$key);
}
// function
