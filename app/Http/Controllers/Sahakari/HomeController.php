<?php

namespace App\Http\Controllers\Sahakari;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'p'=>'',
            'data'=>[
                'ranga' => [
                    'ti'=>'lorem',
                    't' => 'text',
                    'r' => 5,
                    'p'=>'Please Enter Name',
                    'ec'=>'calender',
                    'mask'=>'0000-00-00'
                ]
            ]
        ];
        return view('sahakari.index',compact('data'));
    }
    public function add(){
        
    }
}
