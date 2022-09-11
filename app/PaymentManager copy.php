<?php

namespace App;

use App\Models\Account;
use App\Models\Bank;
use App\Models\paymentSave;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentManager
{
    const foreignChecks=[104,114,150,112,124,201,107,121,106,126,127];
    const idChecks=[140];
    public $banks;
    public $cash;
    public $fy;
    public static $fiscal;
    public $cashChecked = false;
    public $fyChecked = false;

    public static function loadUpdateID($id,$identifire){
        if(!hasPay()){
            return "";
        }
        $payment = paymentSave::where('foreign_id', $id)->where('identifire', $identifire)->first();
        return self::loadUpdate($payment);
    }

    public static function loadUpdate($payment)
    {
        if(!hasPay()){
            return "";
        }
        if($payment!=null){
            $method=$payment->method;
            $detail=json_decode($payment->detail);
            if($payment->method==3){
                $banks=[];
                foreach ($detail->b as $key => $bdetail) {
                    array_push($banks,[
                        'detail'=>DB::table('banks')->where('id',$bdetail[0])->first(['id','name']),
                        'amount'=>$bdetail[1]
                    ]);
                }
                return view('admin.payment.edit',compact('banks','detail','method'))->render();
            }else if($payment->method==1){
                return "Paid Via Cash";

            }else if($payment->method==2){
                return "Paid Via Bank - ".DB::table('banks')->where('id',$detail[0])->first(['name'])->name;
            }
        }
    }

    public static function loadStaticFiscal()
    {
        self::$fiscal = getFiscalYear();
    }
    public static function remove($id, $identifire)
    {
        if(!hasPay()){
            return;
        }
        $payment = paymentSave::where('foreign_id', $id)->where('identifire', $identifire)->first();
        if ($payment != null) {

            $detail = json_decode($payment->detail);
            if (self::$fiscal == null) {
                self::loadStaticFiscal();
            }
            if ($payment != null) {
                $paymentType = $payment->type == 1 ? '-' : '+';
                if ($payment->method == 1) {
                    $fy = self::$fiscal;
                    if ($fy != null) {
                        DB::update("update accounts set amount=amount {$paymentType} {$detail[0]} where identifire='1.1' and fiscal_year_id={$fy->id}");
                    }
                } else if ($payment->method == 2) {
                    DB::update("update banks set balance=balance {$paymentType} {$detail[1]} where id={$detail[0]}");
                } else if ($payment->method == 3) {
                    if ($detail->c > 0) {
                        $fy = self::$fiscal;
                        if ($fy != null) {
                            DB::update("update accounts set amount=amount {$paymentType} {$detail->c} where identifire='1.1' and fiscal_year_id={$fy->id}");
                        }
                        foreach ($detail->b as $key => $bank) {
                            DB::update("update banks set balance=balance {$paymentType} {$bank[1]} where id={$bank[0]}");
                        }
                    }
                }
                $payment->delete();
            }
        }
    }

    public static function update($id, $identifire, $request)
{
        if(!hasPay()){
            return;
        }
        $payment = paymentSave::where('foreign_id', $id)->where('identifire', $identifire)->first();
        if ($payment != null) {
            if (self::$fiscal == null) {
                self::loadStaticFiscal();
            }
            $paymentType = $payment->type == 1 ? '-' : '+';
            $epaymentType = $payment->type == 1 ? '+' : '-';
            $amount = $request->input('expay_amount');
            $detail = json_decode($payment->detail);
            if ($payment->method == 1) {
                $fy = self::$fiscal;
                if ($fy != null) {
                    DB::update("update accounts set amount=amount {$paymentType} {$detail[0]} where identifire='1.1' and fiscal_year_id={$fy->id}");
                    DB::update("update accounts set amount=amount {$epaymentType} {$amount} where identifire='1.1' and fiscal_year_id={$fy->id}");
                    $payment->detail = json_encode([$amount]);
                }
            } else if ($payment->method == 2) {
                DB::update("update banks set balance=balance {$paymentType} {$detail[1]} where id={$detail[0]}");
                DB::update("update banks set balance=balance {$epaymentType} {$detail[1]} where id={$detail[0]}");
                $payment->detail = json_encode([$detail[0], $amount]);
            } else if ($payment->method == 3) {
                $fy = self::$fiscal;
                if ($detail->c > 0) {
                    $data = ['c' => 0, 'b' => []];

                    if ($fy != null) {
                        DB::update("update accounts set amount=amount {$paymentType} {$detail->c} where identifire='1.1' and fiscal_year_id={$fy->id}");
                    }
                    foreach ($detail->b as $key => $bank) {
                        DB::update("update banks set balance=balance {$paymentType} {$bank[1]} where id={$bank[0]}");
                    }

                    $cashAmount = $request->input('expay_custom_cash') ?? 0;
                    $data = ['c' => 0, 'b' => []];
                    if ($cashAmount > 0) {
                        DB::update("update accounts set amount=amount {$epaymentType} {$detail->c} where identifire='1.1' and fiscal_year_id={$fy->id}");

                        $data['c'] = $cashAmount;
                    }
                    if ($request->filled('expay_custom_bank')) {
                        $bank_ids = $request->expay_custom_bank;
                        foreach ($bank_ids as $key => $bank_id) {
                            $bankAmount = $request->input('expay_custom_bank_amount_' . $bank_id);
                            DB::update("update banks set balance=balance {$epaymentType} {$bank[1]} where id={$bank_id}");
                            array_push($data['b'], [$bank_id, $bankAmount]);
                        }
                    }
                    $payment->detail = json_encode($data);
                }
                // dd($payment);
            }
            $payment->save();
        }
    }
    public static function removeMultiple($ids, $identifire)
    {
    }

    function __construct($request, $id, $identifire,$date=null)
    {
        if($date==null){
            $date=str_replace('-','',$request->date);
        }
        if(!hasPay()){
            return;
        }

        $banks = collect([]);
        $data = [];
        if ($request->getMethod() == "POST") {
            if ($request->filled('xpay')) {
                $amount = $request->input('xpay_amount');
                if ($amount > 0) {
                    $method = $request->input('xpay_method');
                    $type = $request->input('xpay');

                    if ($method == 1) {
                        if ($type == 1) {
                            $this->receiveCash($amount);
                        } else if ($type == 2) {
                            $this->payCash($amount);
                        }
                        $data = [$amount];
                    } else if ($method == 2) {
                        $bank_id = $request->input('xpay_bank');
                        if ($type == 1) {
                            $this->receiveBank($bank_id, $amount);
                        } else if ($type == 2) {
                            $this->payBank($bank_id, $amount);
                        }
                        $data = [$bank_id, $amount];
                    } else if ($method == 3) {
                        $cashAmount = $request->input('xpay_custom_cash') ?? 0;
                        $data = ['c' => 0, 'b' => []];
                        if ($cashAmount > 0) {
                            if ($type == 1) {
                                $this->receiveCash($cashAmount);
                            } else if ($type == 2) {
                                $this->payCash($cashAmount);
                            }
                            $data['c'] = $cashAmount;
                        }
                        if ($request->filled('xpay_custom_bank')) {
                            $bank_ids = $request->xpay_custom_bank;
                            foreach ($bank_ids as $key => $bank_id) {
                                $bankAmount = $request->input('xpay_custom_bank_amount_' . $bank_id);
                                if ($type == 1) {
                                    $this->receiveBank($bank_id, $bankAmount);
                                } else if ($type == 2) {
                                    $this->payBank($bank_id, $bankAmount);
                                }
                                array_push($data['b'], [$bank_id, $bankAmount]);
                            }
                        }
                    }

                    $now = Carbon::now()->toDateTimeString();
                    paymentSave::insert([
                        'foreign_id' => $id,
                        'identifire' => $identifire,
                        'type' => $type,
                        'method' => $method,
                        'detail' => json_encode($data, JSON_NUMERIC_CHECK),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
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

    public function receiveCash($amount)
    {
        if ($this->loadCash()) {
            
            // DB::update("update accounts set amount=amount+{$amount} where id={$this->cash->id}");
        }
    }
    public function payCash($amount)
    {
        if ($this->loadCash()) {
            DB::update("update accounts set amount=amount-{$amount} where id={$this->cash->id}");
        }
    }

    function receiveBank($id, $amount)
    {
        $bank = Bank::where('id', $id)->first();
        DB::update("update banks set balance=balance+ {$amount} where id={$id}");
    }
    function payBank($id, $amount)
    {
        $bank = Bank::where('id', $id)->first();
        DB::update("update banks set balance=balance- {$amount} where id={$id}");
    }


}
