<?php

namespace App;

use App\Models\Advance;
use App\Models\Center;
use App\Models\Distributorsell;
use App\Models\Ledger;
use App\Models\Milkdata;
use App\Models\Sellitem;
use App\Models\Snffat;
use App\Models\User;

class LedgerManage
{
    public  $user;

    public function  __construct($user_id)
    {
        $this->user = User::find($user_id);
    }

    /*
    *amounttype[1="CR",2="DR"]
    * "101"= Aalya
    * "102"= "farmer opening balance/advance"
    * "103" = "item sell"
    * "104" = "Farmer Advance"
    * "106" = "Farmer amount paid at Selling item"
    * "107" = "Amount paid by farmer"
    * "108" = "Famer milk Money Adjustment"
    * "109" = "Colsing Balance farmer"
    * "110" = "Automatic payment Given to famer when closing"
    * "116" = "Farmer item return"
    * "117" = "Farmer item return paid cancel"
    * "121" = "Farmer paid for milk"

    * "105" = "Sold to distributer"
    * "114" = "distributer Payment"
    * "115" = "distributer sell cancel"
    * "118" = "Account Adjustment"
    * "119" = "Distributor opening balance"
    * "132" = "Milk total Distributer"
    * "150" = "Milk total Distributer"

    * "112" = "Employee Advaance payment"
    * "303" = "Employee Account Open"
    * "124" = "Employee Salary payment"
    * "122" = "paid amount while billing"
    * "123" = "purchase in billing items"
    * "129" = "Employee Salary For Month"
    * "132" = "Employee closing Balance"
    * "133" = "Employee opening Balance"
    * "301" = "Employee Purchase"
    * "302" = "Employee Payment while Purchase"
    * "113" = "Advance cancel"




    * "125" = "purchase from suppliers"
    * "126" = "paid to suppliers through billing entry"
    * "127" = "Payment to supplier"
    * "128" = "Previous balance of supplier"
    * "128" = "Previous balance of supplier"

    **customer section
    * "130" = "Sold to customer"
    * "131" = "customer Payment"
    * "401" = "Sold to customer old pos"
    * "402" = "customer Payment old pos"
    * "135" = "customer Payment extra"
    * "134" = "customer opening Balance"

    **Expense Section
    * "201" = "index"
    */
    public function addLedger($particular, $type, $amount, $date, $identifier, $foreign_id = null)
    {
        $nepalidate = new NepaliDate($date);
        $l = new \App\Models\Ledger();
        $l->amount = $amount;
        $l->title = $particular;
        $l->date = $date;
        $l->identifire = $identifier;
        $l->foreign_key = $foreign_id;
        $l->user_id = $this->user->id;
        $l->year = $nepalidate->year;
        $l->month = $nepalidate->month;
        $l->session = $nepalidate->session;
        $l->type = $type;

        $l->save();
        return $l;
    }





    public static  function delLedger($ledgers)
    {
        foreach ($ledgers as $ledger) {
            $user = User::find($ledger->user_id);
            $ledgers = Ledger::where('id', '>', $ledger->id)->where('user_id', $ledger->user_id)->orderBy('id', 'asc')->get();
            $track = 0;
            //find first point
            if ($ledger->cr > 0) {
                $track = (-1) * $ledger->cr;
            }
            if ($ledger->dr > 0) {
                $track = $ledger->dr;
            }
            // echo 'first' . $track . "<br>";

            //find old data

            if ($ledger->type == 1) {
                $track += $ledger->amount;
            } else {
                $track -= $ledger->amount;
            }


            $ledger->delete();

            foreach ($ledgers as $l) {

                if ($l->type == 1) {
                    $track -= $l->amount;
                } else {
                    $track += $l->amount;
                }

                if ($track < 0) {
                    $l->cr = (-1) * $track;
                    $l->dr = 0;
                } else {
                    $l->dr = $track;
                    $l->cr = 0;
                }
                $l->save();
            }

            $t = 0;
            if ($track > 0) {
                $t = 2;
            } else if ($track < 0) {
                $t = 1;
                $track = (-1) * $track;
            }


            $user->amount = $track;
            $user->amounttype = $t;
            $user->save();
        }
    }

    public static function updateLedger($ledger, $amount, $type = null, $title = null)
    {
        $ledgers = Ledger::where('id', '>', $ledger->id)->where('user_id', $ledger->user_id)->orderBy('id', 'asc')->get();
        $track = 0;

        //find first point
        if ($ledger->cr > 0) {
            $track = (-1) * $ledger->cr;
        }
        if ($ledger->dr > 0) {
            $track = $ledger->dr;
        }

        // echo 'first'.$track."<br>";

        //find old data

        if ($ledger->type == 1) {
            $track += $ledger->amount;
        } else {
            $track -= $ledger->amount;
        }

        // echo 'second'.$track."<br>";

        if ($type == null) {
            $type = $ledger->type;
        }
        if ($title == null) {
            $title = $ledger->title;
        }
        //set new data
        if ($type == 1) {
            $track -= $amount;
        } else {
            $track += $amount;
        }

        // echo 'third'.$track."<br>";


        $ledger->amount = $amount;
        $ledger->type = $type;
        $ledger->title = $title;

        if ($track < 0) {
            $ledger->cr = (-1) * $track;
            $ledger->dr = 0;
        } else {
            $ledger->dr = $track;
            $ledger->cr = 0;
        }
        $ledger->save();

        foreach ($ledgers as $l) {

            if ($l->type == 1) {
                $track -= $l->amount;
            } else {
                $track += $l->amount;
            }

            if ($track < 0) {
                $l->cr = (-1) * $track;
                $l->dr = 0;
            } else {
                $l->dr = $track;
                $l->cr = 0;
            }
            $l->save();

            // echo $l->title . ",".$track."<br>";
        }

        $t = 0;
        if ($track > 0) {
            $t = 2;
        } else if ($track < 0) {
            $t = 1;
            $track = (-1) * $track;
        }

        $user = User::where('id', $ledger->user_id)->first();
        $user->amount = $track;
        $user->amounttype = $t;
        $user->save();
    }



    public static function farmerReport($user_id, $range, $needledger = false)
    {
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
        }
        $farmer1->fpaid = (Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '106')->sum('amount')
        + Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '107')->sum('amount'));
        $farmer1->due = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '103')->sum('amount');;

        $previousMonth = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '101')->sum('amount');
        // $previousMonth1 = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 1)->sum('amount');
        // $previousBalance = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 2)->sum('amount');
        // $previousMonth1 = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->where('type', 1)->sum('amount');
        // $ledgers= Ledger::where('user_id',$user_id)->where('date','<',$range[1])->where('identifire','!=',109)->where('identifire','!=',120)->orderBy('date','asc')->orderBy('id','asc')->get();
        $previousMonth1 = 0;
        $previousBalance = 0;
        // $n1=Ledger::where('user_id',$user_id)->where('date','<',$range[1])->where('identifire','!=',109)->where('identifire','!=',120)->where('type',1)->sum('amount');
        // $n2=Ledger::where('user_id',$user_id)->where('date','<',$range[1])->where('identifire','!=',109)->where('identifire','!=',120)->where('type',2)->sum('amount');
        $n3 = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '120')->get();
        $n1 = Ledger::where('user_id', $user_id)->where('date', '<', $range[1])->where('type', 1)->sum('amount');
        $n2 = Ledger::where('user_id', $user_id)->where('date', '<', $range[1])->where('type', 2)->sum('amount');

        $prev = $n2 - $n1;
        foreach ($n3 as  $value) {
            if ($value->type == 1) {
                $prev -= $value->amount;
            } else {
                $prev += $value->amount;
            }
        }


        if ($prev < 0) {
            $previousBalance = -1 * $prev;
        } else {
            $previousMonth1  = $prev;
        }

        $farmer1->advance = (float)(Advance::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->sum('amount'));
        $farmer1->prevdue = (float)$previousMonth + (float)$previousMonth1;
        $farmer1->prevbalance = (float)$previousBalance;
        $farmer1->paidamount = (float)Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->where('identifire', '121')->where('type', 1)->sum('amount');
        $balance = $farmer1->grandtotal + $farmer1->balance - $farmer1->prevdue - $farmer1->advance - $farmer1->due - $farmer1->paidamount + $farmer1->prevbalance - $farmer1->bonus + $farmer1->fpaid;
        $farmer1->balance = 0;
        $farmer1->nettotal = 0;
        if ($balance < 0) {
            $farmer1->balance = (-1) * $balance;
        }
        if ($balance > 0) {
            $farmer1->nettotal = $balance;
        }

        if ($needledger) {

            $farmer1->ledger = Ledger::where('user_id', $user_id)->where('date', '>=', $range[1])->where('date', '<=', $range[2])->orderBy('id', 'asc')->get();
        } else {
            $farmer1->ledger = [];
        }

        return $farmer1;
    }


}
