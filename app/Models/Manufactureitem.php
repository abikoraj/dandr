<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufactureitem extends Model
{
    use HasFactory;

    public function manufacture(){
        return $this->belongsTo(Manufacture::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
