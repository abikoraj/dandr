<?php
define('farmer',1);
define('distributer',2);
define('employee',3);
define('supplier',4);
define('customer',5);

function _nepalidate($date)
{
    $year = (int)($date / 10000);
    $date = $date % 10000;
    $month = (int)($date / 100);
    $day = (int)($date % 100);
    return $year . "-" . ($month < 10 ? "0" . $month : $month) . "-" . ($day < 10 ? "0" . $day : $day);
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

// function 
