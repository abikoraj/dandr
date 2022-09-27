<?php

use App\Models\Customer;
use App\Models\User;

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
        $user->customer=$customer;
    }
    return $user;
}
