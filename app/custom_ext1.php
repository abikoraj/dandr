<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

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


function getUserBalance($arr=[]){
    $query=getUserWhereInQuery($arr);
    return DB::select(" select id,name,
    (ifnull((select sum(amount) from ledgers where user_id=users.id and type=2),0)
    - ifnull((select sum(amount) from ledgers where user_id=users.id and type=1),0) ) as balance
    from users where id in {$query}
    ");
}

function getUserWhereInQuery($arr = [])
{
    $union = [];

    foreach ($arr as $key => $table) {
        array_push($union, "(select user_id from {$table})");
        // $users = array_merge($users, DB::select("select {$selectRaw} from users u join {$table} t on t.user_id=u.id"));
    }
    $whereUnion = implode(' union ', $union);
    return "(select user_id from ({$whereUnion}) u)";
}

function getUsers($arr = [], $columns = [])
{
    $users = [];
    if (count($arr) == 0) {
        return DB::select('select * from users');
    } else {


        $select = [];
        foreach ($columns  as $key => $column) {
            array_push($select, $column);
        }

        $selectRaw = count($columns) > 0 ? implode(',', $select) : ' * ';
        $union = [];

        foreach ($arr as $key => $table) {
            array_push($union, "(select user_id from {$table})");
            // $users = array_merge($users, DB::select("select {$selectRaw} from users u join {$table} t on t.user_id=u.id"));
        }
        $whereUnion = implode(' union ', $union);
        // dd("select {$selectRaw} from users where id in (select user_id from ({$whereUnion}) u)");
        $users = DB::select("select {$selectRaw} from users where id in (select user_id from ({$whereUnion}) u)");
    }
    return $users;
}

function isPositive($num)
{
}
function getPositive($num)
{
    return isPositive($num) ? $num : (-1 * $num);
}


function isSelected($val1, $val2)
{
    return $val1 == $val2 ? 'selected' : '';
}

function getWhereIn($collection, $column = 'id')
{

    if (gettype($collection) == 'array') {
        $collection = collect($collection);
    }

    return "(" . implode(',', $collection->pluck($column)->toArray()) . ")";
}


function isGET(){
    return request()->getMethod()=="GET";
}