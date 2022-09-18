<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MilkReportController extends Controller
{
    public function fy(Request $request)
    {
        if($request->getMethod()=="POST"){

        }else{
            return view('admin.report.milk.fy.index');
        }
    }
}
