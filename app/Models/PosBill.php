<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosBill extends Model
{
    use HasFactory;
    public function billItems(){
        return $this->hasMany(PosBillItem::class);
    }
    public function payment(){
        return $this->hasOne(Payment::class,'foreign_key','id');
    }
}
