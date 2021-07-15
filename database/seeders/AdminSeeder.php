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
        $user->name=env('APP_NAME',"Nawa Durga");
        $user->phone=env('authphone',"9852059171");
        $user->password=bcrypt('admin');
        $user->role=0;
        $user->address="Ramailo, Morang";
        $user->save();
    }
}
