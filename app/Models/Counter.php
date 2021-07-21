<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;

    protected $casts=[
        'last'=>'datetime'
    ];
    public function statuses(){
        return  $this->hasMany(CounterStatus::class);
    }

    public function currentStatus(){
        return CounterStatus::where('status',1)->where('counter_id',$this->id)->first();
    }

    public function hasStatus(){
        return $this->status==1;
    }
}
