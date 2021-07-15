<?php

namespace App\Models;

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

    public function user(){
        return $this->belongsTo(User::class);
    }
}
