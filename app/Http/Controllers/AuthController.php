<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        if($request->getMethod()=="POST"){
            // dd($request->all());

            $request->validate([
                'phone' => 'required|numeric',
                'password' => 'required|string',

            ]);
            $phone=$request->phone;
            $password=$request->password;
            if (Auth::attempt(['phone' => $phone, 'password' => $password], true)) {
                    return redirect()->route(Auth::user()->getRole().'.dashboard');
            }else{
                return redirect()->back()->withErrors('Credential do not match');
            }
        }else{
            return view('admin.auth.login');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }
}
