<?php

namespace App;

use App\Models\Account;
use App\Models\Bank;
use App\Models\paymentSave;
use App\PaymentManager as AppPaymentManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentManager
{
    const foreignChecks = [104, 114, 150, 112, 124, 201, 107, 121, 106, 126, 127];
    const idChecks = [140];
    public $banks;
    public $cash;
    public $fy;
    public static $fiscal;
    public $cashChecked = false;
    public $fyChecked = false;

    public static function loadUpdateID($id, $identifire)
    {
    //     if (!hasPay()) {
    //         return "";
    //     }
    //    $ledgers
    //     $ledgers=DB::table('account_ledgers')->where('foreign_key',$id)->where('identifier')->
    }

    public static function loadUpdate($identifire,$id)
    {
        if (!hasPay()) {
            return "";
        }

        $ledgers=DB::table('account_ledgers')
        ->join('accounts','accounts.id','=','account_ledgers.account_id')
        ->where('account_ledgers.identifier',$identifire)->where('foreign_key',$id)->get(['account_ledgers.id','account_ledgers.amount','accounts.name']);
        return view('admin.payment.edit',compact('ledgers'))->render();
       
    }

    public static function loadStaticFiscal()
    {
        self::$fiscal = getFiscalYear();
    }
    public static function remove($id, $identifire)
    {
        if (!hasPay()) {
            return;
        }
        delACByNOID($identifire,$id);
    }

    public static function update( $request)
    {
        if (!hasPay()) {
            return;
        }
        $del_ids=[];
        if($request->filled('xpay_ledger_ids')){
            foreach ($request->xpay_ledger_ids as $key => $ledger_id) {
                $ledgerAmount=$request->input('xpay_ledger_'.$ledger_id);
                if($ledgerAmount!=null){
                    if($ledgerAmount!=0){
                        updateLedgerAmount($ledger_id,$ledgerAmount);
                    }else{
                        array_push($del_ids,$ledger_id);
                    }
                }else{
                    array_push($del_ids,$ledger_id);

                }
            }
        }
        if(count($del_ids)>0){

            DB::table('account_ledgers')->whereIn('id',$del_ids)->delete();
        }
      
        // delACByNOID($id,$identifire);
        // new PaymentManager($request,$id,$identifire,$title,$date);
    }
    public static function removeMultiple($ids, $identifire)
    {
    }

    function __construct($request, $id, $identifire, $title = '', $date = null)
    {
        if ($date == null) {
            $date = str_replace('-', '', $request->date);
        }
        if (!hasPay()) {
            return;
        }

        if ($request->getMethod() == "POST") {
            if ($request->filled('xpay')) {
                $amount = $request->input('xpay_amount');
                if ($amount > 0) {
                    $method = $request->input('xpay_method');
                    $type = $request->input('xpay');
                    if ($method == 1) {
                        if ($type == 1) {
                            $this->receiveCash($amount, $date, $identifire, $id, $title);
                        } else if ($type == 2) {
                            $this->payCash($amount, $date, $identifire, $id, $title);
                        }
                        $data = [$amount];
                    } else if ($method == 2) {
                        $acc_id = $request->input('xpay_bank');
                        if ($type == 1) {
                            $this->receiveBank($acc_id, $amount, $date, $identifire, $id, $title);
                        } else if ($type == 2) {
                            $this->payBank($acc_id, $amount, $date, $identifire, $id, $title);
                        }
                    } else if ($method == 3) {
                        $cashAmount = $request->input('xpay_custom_cash') ?? 0;
                        if ($cashAmount > 0) {
                            if ($type == 1) {
                                $this->receiveCash($cashAmount, $date, $identifire, $id, $title);
                            } else if ($type == 2) {
                                $this->payCash($cashAmount, $date, $identifire, $id, $title);
                            }
                        }
                        if ($request->filled('xpay_custom_bank')) {
                            $acc_ids = $request->xpay_custom_bank;
                            foreach ($acc_ids as $key => $acc_id) {
                                $bankAmount = $request->input('xpay_custom_bank_amount_' . $acc_id);
                                if ($type == 1) {
                                    $this->receiveBank($acc_id, $bankAmount, $date, $identifire, $id, $title);
                                } else if ($type == 2) {
                                    $this->payBank($acc_id, $bankAmount, $date, $identifire, $id, $title);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    public function loadFY()
    {
        if ($this->fy == null && !$this->fyChecked) {
            $this->fy = getFiscalYear();
            $this->fyChecked = true;
        }
        return $this->fy != null;
    }
    public function loadCash()
    {

        if (!$this->loadFY()) {
            return false;
        }

        if ($this->cash == null && !$this->cashChecked) {
            $this->cash = Account::where('identifire', '1.1')->where('fiscal_year_id', $this->fy->id)->first();
            $this->cashChecked = true;
        }

        return $this->cash != null;
    }

    public function receiveCash($amount, $date, $identifire, $id, $title = '')
    {
        pushCASH(2, $amount, $identifire, $date, $title, $id);
    }
    public function payCash($amount, $date, $identifire, $id, $title = '')
    {
        pushCASH(1, $amount, $identifire, $date, $title, $id);
    }

    function receiveBank($acc_id, $amount, $date, $identifire, $ref_id, $title = '')
    {

        pushBank($acc_id, 2, $amount, $identifire, $date, $title, $ref_id);
    }
    function payBank($acc_id, $amount, $date, $identifire, $ref_id, $title = '')
    {
        pushBank($acc_id, 1, $amount, $identifire, $date, $title, $ref_id);
    }
}
