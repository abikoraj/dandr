<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        if(env('front',0)==1){
            return view('front.index');
        }else{
            return redirect()->route('admin.dashboard');

        }
    }
}
