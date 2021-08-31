<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosSetting extends Model
{
    use HasFactory;

    protected $casts = [
        'direct' => 'boolean',
        'open' => 'boolean',
    ];

    public function requests(){
        $setting=PosSetting::first();
        if($setting==null){
            return [];
        }
        return CounterStatus::where('date',$this->date)->with('counter')->get();
    }
   
}
