<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function getCustomer($phone, $name)
{

    $user = User::where('phone', $phone)->first();
    if (!$user) {
        $user = new User();
        $user->phone = $phone;
        $user->name = $name;
        $user->address = "";
        $user->role = 2;
        $user->amount = 0;
        $user->password = bcrypt($phone);
        $user->save();
        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->center_id = 0;
        $customer->foreign_id = 0;
        $customer->save();
        $user->customer = $customer;
    }
    return $user;
}


function canDeleteChalan($id)
{
    return (DB::table('chalan_sales')->where('employee_chalan_id', $id)->count() + DB::table('chalan_payments')->where('employee_chalan_id', $id)->count()) <= 0;
}

function getUsers($arr = [], $columns = [])
{
    $users = [];
    if (count($arr) == 0) {
        return DB::select('select * from users');
    } else {

        if (count($columns) == 0) {
            foreach ($arr as $key => $table) {
                $users = array_merge($users, DB::select("select u.* from users u join {$table} t on t.user_id=u.id"));
            }
        } else {
            $select =[];
            foreach ($columns  as $key => $column) {
                array_push($select,'u.'.$column);
            }
            $selectRaw=implode(',',$select);
            foreach ($arr as $key => $table) {
                $users = array_merge($users, DB::select("select {$selectRaw} from users u join {$table} t on t.user_id=u.id"));
            }
        }
    }
    return $users;
}

function isPositive($num){

}
function getPositive($num){
    return isPositive($num)?$num:(-1*$num);
}
