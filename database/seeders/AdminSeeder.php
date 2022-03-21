<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=new User();
        $user->name="Krishna Rai";
        $user->phone=env('authphone',"9852078275");
        $user->password=bcrypt('admin');
        $user->role=0;
        $user->address="Biratnagar - 3";
        $user->save();
    }
}
