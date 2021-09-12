<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    public function hasItems(){
        return OfferItem::where('offer_id',$this->id)->count()>0;
    }
}
