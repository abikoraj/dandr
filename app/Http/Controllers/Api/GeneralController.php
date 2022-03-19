<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    public function info()
    {
        return response()->json([
            'name'=>env('companyName'),
            'phone'=>env('companyphone'),
            'reg'=>env('companyRegNO'),
            'panvat'=>env('companyVATPAN'),
            'usetax'=>env('companyUseTax'),
            'billtitle'=>env('companyBillTitle'),
            'address'=>env('companyAddress')
        ]);
    }
}
