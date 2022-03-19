<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|string',

        ]);
        $phone = $request->phone;
        $password = $request->password;
        if (Auth::attempt(['phone' => $phone, 'password' => $password], true)) {
            $user = Auth::user();
            $token = $user->createToken('API-KEY')->accessToken;
            return response($token);
        } else {
            abort(401, 'Credential do not match');
        }
    }

    public function addPosUser(Request $request)
    {
        $user = Auth::user();
        // return response($user->phone);
        if ($user->phone == env('authphone', 9800916365)) {
            $newuser = User::where('phone', $request->phone)->first();
            if ($newuser == null) {
                $newuser = new User();
                // $newuser->email = $request->phone . '@' . env('domain', 'needtechnosoft.com.np');
                $newuser->phone = $request->phone;
                $newuser->role = 1;
            }
            $newuser->name = $request->name;
            $newuser->address = $request->address??"";
            $newuser->password = bcrypt($request->pass);
            $newuser->save();

            $permission = UserPermission::where('user_id', $user->id)->where('code', '09.05')->first();
            if ($permission == null) {
                $permission = new UserPermission();
                $permission->user_id = $user->id;
                $permission->code = '09.05';
            }
            $permission->enable = 1;
            $permission->save();
            return response('ok');
        } else {
            abort(401);
        }
    }
}
