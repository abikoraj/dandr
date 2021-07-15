<?php

use App\Models\Distributer;
use App\Models\Distributorsell;
use App\Models\Ledger;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('clear', function () {
    $user=User::find(685);
    Ledger::where('user_id',685)->delete();
    Distributorsell::where('distributer_id',1)->delete();
    $user->amount=0;
    $user->save();
})->purpose('clear 685');

Artisan::command('password', function(){
     User::where('role',1)->orWhere('role',2)->update(['password'=>bcrypt(12345)]);
});
