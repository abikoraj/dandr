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

    public function currentStatus($date=null){
        $setting=PosSetting::first();
        if($setting==null){
            return null;
        }
        if($date==null){

            return CounterStatus::where('date',$setting->date)->where('counter_id',$this->id)->first();
        }else{
            $status=CounterStatus::where('date',$date)->where('counter_id',$this->id)->first();
            if($status==null){
                $status=new CounterStatus();
                $status->date=$date;
                $status->counter_id=$this->id;
                $status->save();
            }
            return $status;
        }
    }


    public function hasBill(){
        return PosBill::where('counter_id',$this->id)->count()>0;
    }
    public function hasStatus($date=null){
        if($date==null){

            $setting=PosSetting::first();
            if($setting==null){
                return false;
            }
            if($setting->open==0){
                return false;
            }
            $date=$setting->date;
        }
        return CounterStatus::where('date',$date)->where('counter_id',$this->id)->count()>0;
    }
}
