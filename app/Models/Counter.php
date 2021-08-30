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
        $setting=PosSetting::first();
        if($setting==null){
            return null;
        }
        return CounterStatus::where('date',$setting->date)->where('counter_id',$this->id)->first();
    }

    public function hasStatus(){
        $setting=PosSetting::first();
        if($setting==null){
            return false;
        }
        return CounterStatus::where('date',$setting->date)->where('counter_id',$this->id)->count()>0;
    }
}
