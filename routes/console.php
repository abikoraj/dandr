<?php

use App\Models\Distributer;
use App\Models\Distributorsell;
use App\Models\FiscalYear;
use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
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

Artisan::command('loadFiscalYears', function () {
    $bs=(new NepaliDate(20000101))->getBS();

    for ($i=75; $i < 89; $i++) { 
        $fy=new FiscalYear();
        $n=$i+1;
        $fy->name="0".$i."/0".$n;
        $fy->startdate=(20000000)+($i*10000)+400+1;
        $fy->enddate=(20000000)+(($i+1)*10000)+300+$bs[$n][3];
        $fy->save();
        echo $fy->name.",".$fy->startdate." - ".$fy->enddate ."||".$bs[$n][0]. PHP_EOL;
    }
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



