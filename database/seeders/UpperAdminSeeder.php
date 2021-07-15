<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UpperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=new User();
        $user->name="Nawa Durga";
        $user->phone="9800916365";
        $user->password=bcrypt('admin');
        $user->role=-1;
        $user->address="Ramailo, Morang";
        $user->save();
    }
}
