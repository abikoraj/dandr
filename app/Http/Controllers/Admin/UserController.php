<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        return view('admin.users.index');
    }

    public function userAdd(Request $request){
        $request->validate([
            'password' => 'required|min:8',
            'phone' => 'required|min:10|unique:users,phone'

        ]);
        $user = new User();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->password = bcrypt($request->password);
        $user->role = 0;
        $user->save();
        return redirect()->back()->with('message','User added successfully!');
        // dd($request->all());
    }

    public function delete($id){
        $user = User::where('id',$id)->where('role',0)->delete();
        return redirect()->back()->with('message','Delete successfully!');
    }

    public function update(Request $request, $update){
        // dd($request->all());
        $user = User::where('id',$update)->where('role',0)->first();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->save();
        return redirect()->back()->with('message','Update successfully!');
    }

    public function changePassword(Request $request){
        // dd($request->all());
        $request->validate([
            'n_password' =>'required|min:8'
            ],
            [
            'n_password.min' => 'Password should be at least 8 characters !'
        ]);
        $user = User::where('id',$request->id)->where('role',0)->first();
        if (Hash::check($request->c_password, $user->password)){
            $user->password = bcrypt($request->n_password);
            $user->save();
            return redirect()->back()->with('message','Password has been changed successfully !');
        }else{
          return redirect()->back()->with('message_danger','Current Password does not matched !');
        }
    }

    public function nonSuperadminChangePassword(Request $request, $id){
        if($request->isMethod('post')){
            $request->validate([
                'n_password' =>'required|min:8'
                ],
                [
                'n_password.min' => 'Password should be at least 8 characters !'
            ]);
            $user = User::where('id',$id)->where('role',0)->first();
            // dd($user);
            $user->password = bcrypt($request->n_password);
            $user->save();
            return redirect()->back()->with('message','Password has been changed successfully !');
        }else{
            $user = User::where('id',$id)->where('role',0)->first();
            return view('admin.users.nonsuperadmin',compact('user'));
        }
    }
}
