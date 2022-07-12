<?php

namespace App\Models;

use App\PaymentManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    public function getForeign(){
        if($this->identifire==102){
            return Advance::find($this->foreign_key);
        }
        if($this->identifire==103){
            return Sellitem::find($this->foreign_key);
        }
        if($this->identifire==114){
            return Distributorsell::find($this->foreign_key);
        }
        if($this->identifire==105){
            return Distributorsell::where('id',$this->foreign_key)->first();
        }
    }

    public function hasPayment()
    {
        return   in_array($this->identifire, PaymentManager::foreignChecks)||in_array($this->identifire, PaymentManager::idChecks);


    }


    public function canChangeType()
    {
        return in_array($this->identifire,[101,102,119,113,128,134]);
    }

    public function updatePayment($request){
        if(in_array($this->identifire, PaymentManager::foreignChecks)){
             PaymentManager::update($this->foreign_key,$this->identifire,$request);
        }
        if(in_array($this->identifire, PaymentManager::idChecks)){
             PaymentManager::update($this->id,$this->identifire,$request);
        }
    }
    public function deletePayment(){
        if(in_array($this->identifire, PaymentManager::foreignChecks)){
             PaymentManager::remove($this->foreign_key,$this->identifire);
        }
        if(in_array($this->identifire, PaymentManager::idChecks)){
             PaymentManager::remove($this->id,$this->identifire);
        }
    }
    public function getPaymentData(){
        if(in_array($this->identifire, PaymentManager::foreignChecks)){
            return PaymentManager::loadUpdateID($this->foreign_key,$this->identifire);
        }
        if(in_array($this->identifire, PaymentManager::idChecks)){
            return PaymentManager::loadUpdateID($this->id,$this->identifire);
        }
        return "";
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
