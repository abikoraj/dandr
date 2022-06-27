<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public function open()
    {
        $this->opening=currentStock()->sum;
        $this->save();
    }
    public function close()
    {
        $this->closing=currentStock()->sum;
        $this->save();

    }
}
