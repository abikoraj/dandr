<?php

namespace App\Models;

use App\NepaliDate;
use App\NepaliDateHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'direct' => 'boolean',
        'open' => 'boolean',
    ];

    public static function getDate():int{
        $setting=PosSetting::first();
        if($setting==null){
            $date=Carbon::now();
            $d=new NepaliDateHelper();
            $nepDate=$d->eng_to_nep($date->year,$date->month,$date->day);
            return $nepDate['year']*10000+$nepDate['month']*100+$nepDate['day'];
        }
        return $setting->date;

    }
    public function requests(){
        $setting=PosSetting::first();
        if($setting==null){
            return [];
        }
        return CounterStatus::where('date',$this->date)->where('status',1)->with('counter')->get();
    }
    public function fiscalYear(){
        return FiscalYear::where('startdate','<=',$this->date)->where('enddate','>=',$this->date)->first();

    }


}
