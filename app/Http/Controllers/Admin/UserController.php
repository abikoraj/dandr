<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiPermission;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $data=Employee::pluck('user_id');
        $emp_users=User::whereIn('id',$data)->get();
        return view('admin.users.index',compact('emp_users'));
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
        $user = User::where('id',$request->id)->first();
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
            $user = User::where('id',$id)->first();
            // dd($user);
            $user->password = bcrypt($request->n_password);
            $user->save();
            return redirect()->back()->with('message','Password has been changed successfully !');
        }else{
            $user = User::where('id',$id)->first();
            return view('admin.users.nonsuperadmin',compact('user'));
        }
    }

    public function per(Request $request,User $user){
        if($request->getMethod()=="POST"){
            UserPermission::where('user_id',$user->id)->update([
                'enable'=>0
            ]);
            if($request->filled('codes')){
                foreach ($request->codes as $key => $code) {
                    $permission=UserPermission::where('user_id',$user->id)->where('code',$code)->first();
                    if($permission==null){
                        $permission=new UserPermission();
                        $permission->user_id=$user->id;
                        $permission->code=$code;
                    }
                    $permission->enable=1;
                    $permission->save();
                }
            }
            return redirect()->back();
        }else{
            $roles = config('roles');
            $per=UserPermission::where('user_id',$user->id)->where('enable',1)->get();
            return view('admin.users.per.index',compact('roles','per','user'));
        }
    }


    public function perAPI(Request $request,User $user){
        if($request->getMethod()=="POST"){
            $per=ApiPermission::where('user_id',$user->id)->first();
            $centers=[];
            if($request->filled('centers')){
                $centers=$request->centers;
            }
            if($per==null){
                $per=new ApiPermission();
                $per->user_id=$user->id;

            }
            $data=(object)[
                'centers'=>$centers
            ];
            $per->data=json_encode($data);
            $per->save();
            
            return redirect()->back();
        }else{
            $centers=DB::select('select id,name from centers');
            $per=ApiPermission::where('user_id',$user->id)->first();
            if($per!=null){

                $data=json_decode( $per->data);
            }else{
                $data=(object)[
                    'centers'=>[]
                ];
            }
            // dd($data);
            return view('admin.users.per.api',compact('centers','data','user'));
        }
    }

    public function mobilePermission(Request $request,$user)
    {
        if($request->getMethod()=="POST"){

        }else{
            $permission=DB::table('phone_configs')->where('user_id',$user)->first();
            if($permission==null){
                $permission=(object)[
                    'uuid'=>'',
                    'use_sellitem'=>0,
                    'use_milk'=>0,
                    'use_snffat'=>0,
                    'use_sync'=>0,
                ];
            }
        }
    }

}
