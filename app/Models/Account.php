<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;

    public function fixedAssetTotal(){
        return DB::table('fixed_assets')->where('account_id',$this->id)->sum('amount')??0;
    }
    public function bankBalance(){
        return DB::table('banks')->where('account_id',$this->id)->sum('balance')??0;
    }
    public function fiscalyear(){
        return $this->belongsTo(FiscalYear::class,'fiscal_year_id','id');
    }
}
