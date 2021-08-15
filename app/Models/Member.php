<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
  
    public function farmer()
    {
    
        return $this->hasOne(Farmer::class,'user_id','user_id');
    }

    public function employee()
    {
    
        return $this->hasOne(Employee::class,'user_id','user_id');
    }
}
