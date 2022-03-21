<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    //
    public function supplier(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            return view('admin.import.supplier');
        }
    }
}
