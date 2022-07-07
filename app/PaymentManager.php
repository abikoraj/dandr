<?php
namespace App;

use App\Models\Account;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;

class PaymentManager
{
    public $banks;
    public $cash;
    public $fy;
    public $cashChecked=false;
    public $fyChecked=false;



    function __construct(){
        $banks=collect([]);
    }
    public function loadFY()
    {
        if($this->fy==null && !$this->fyChecked){
            $this->fy=getFiscalYear();
            $this->fyChecked=true;
        }
        return $this->fy!=null;
    }
    public function loadCash(){

        if(!$this->loadFY()){
            return false;
        }

        if($this->cash==null && !$this->cashChecked){
            $this->cash=Account::where('identifire','1.1')->where('fiscal_year_id',$this->fy->id)->first();
            $this->cashChecked=true;
        }

        return $this->cash!=null;
    }
    public function receiveCash($amount)
    {
       if($this->loadCash()){
            DB::update("update accounts set amount=amount+{$amount} where id={$this->cash->id}");
        }
    }
    public function payCash($amount)
    {
       if($this->loadCash()){
            DB::update("update accounts set amount=amount-{$amount} where id={$this->cash->id}");
        }
    }

    function receiveBank($id,$amount){
        $bank=Bank::where('id',$id)->first();
        DB::update("update banks set balance=balance+ {$amount} where id={$id}");
    }
    function payBank($id,$amount){
        $bank=Bank::where('id',$id)->first();
        DB::update("update banks set balance=balance- {$amount} where id={$id}");
    }
}
