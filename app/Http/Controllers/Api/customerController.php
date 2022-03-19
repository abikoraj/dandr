<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customerController extends Controller
{
    public function index($center_id){
        $customers=DB::select("select m.name,m.phone,m.address,c.panvat,c.foreign_id as id from customers c join member m on m.user_id=c.user_id where c.center_id=?",[$center_id]);
        return response()->json('customers');
    }

}
