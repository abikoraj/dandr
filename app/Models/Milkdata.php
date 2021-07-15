<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milkdata extends Model
{
    use HasFactory;

    public function user(){
        return \App\Models\User::where('id',$this->user_id)->first();
        // return $this->belongsTo(User::class);
    }
}
