<?php

use App\Models\Bank;
use App\Models\Distributer;
use App\Models\Distributorsell;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\Ledger;
use App\Models\PaymentGateway;
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
Artisan::command('loadBanks', function () {
    $faker = Faker\Factory::create();
    $banks=["Nepal Bank Ltd. (NBL)",
    "Rastriya Banijya Bank Ltd. (RBB)",
    "Nabil Bank Ltd. (NABIL)",
    "Nepal Investment Bank Ltd. (NIBL)",
    "Standard Chartered Bank Nepal Ltd. (SCBNL)",
    "Himalayan Bank Ltd. (HBL)",
    "Nepal SBI Bank Ltd. (NSBI)",
    "Nepal Bangladesh Bank Ltd. (NBB)",
    "Everest Bank Ltd. (EBL)",
    "Bank of Kathmandu Lumbini Ltd. (BOK)",
    "Nepal Credit and Commerce Bank Ltd. (NCC)",
    "NIC ASIA Bank Ltd. (NIC)",
    "Machhapuchhre Bank Ltd. (MBL)",
    "Kumari Bank Ltd. (Kumari)",
    "Laxmi Bank Ltd. (Laxmi)",
    "Siddhartha Bank Ltd. (SBL)",
    "Agriculture Development Bank Ltd. (ADBNL)",
    "Global IME Bank Ltd. (Global)",
    "Citizens Bank International Ltd. (Citizens)",
    "Prime Commercial Bank Ltd. (Prime)",
    "Sunrise Bank Ltd. (Sunrise)",
    "NMB Bank Ltd. (NMB)",
    "Prabhu Bank Ltd. (PRABHU)",
    "Mega Bank Nepal Ltd. (Mega)",
    "Civil Bank Ltd. (Civil)",
    "Century Commercial Bank Ltd. (Century)",
    "Sanima Bank Ltd. (Sanima)"];
    $phone=9800110000;
    for ($i=0; $i < 5; $i++) { 
        $bank=new Bank();
        $bank->name=$banks[ mt_rand(0,(count($banks)-1))];
        $bank->address=$faker->address;
        $bank->phone=$faker->e164PhoneNumber;
        $bank->accno=$faker->bankAccountNumber;
        $bank->save();
    }
});
Artisan::command('loadGateways', function () {
    $faker = Faker\Factory::create();
    $banks=["Nepal Bank Ltd. (NBL)",
    "Rastriya Banijya Bank Ltd. (RBB)",
    "Nabil Bank Ltd. (NABIL)",
    "Nepal Investment Bank Ltd. (NIBL)",
    "Standard Chartered Bank Nepal Ltd. (SCBNL)",
    "Himalayan Bank Ltd. (HBL)",
    "Nepal SBI Bank Ltd. (NSBI)",
    "Nepal Bangladesh Bank Ltd. (NBB)",
    "Everest Bank Ltd. (EBL)",
    "Bank of Kathmandu Lumbini Ltd. (BOK)",
    "Nepal Credit and Commerce Bank Ltd. (NCC)",
    "NIC ASIA Bank Ltd. (NIC)",
    "Machhapuchhre Bank Ltd. (MBL)",
    "Kumari Bank Ltd. (Kumari)",
    "Laxmi Bank Ltd. (Laxmi)",
    "Siddhartha Bank Ltd. (SBL)",
    "Agriculture Development Bank Ltd. (ADBNL)",
    "Global IME Bank Ltd. (Global)",
    "Citizens Bank International Ltd. (Citizens)",
    "Prime Commercial Bank Ltd. (Prime)",
    "Sunrise Bank Ltd. (Sunrise)",
    "NMB Bank Ltd. (NMB)",
    "Prabhu Bank Ltd. (PRABHU)",
    "Mega Bank Nepal Ltd. (Mega)",
    "Civil Bank Ltd. (Civil)",
    "Century Commercial Bank Ltd. (Century)",
    "Sanima Bank Ltd. (Sanima)"];
    $phone=9800110000;
    for ($i=0; $i < 5; $i++) { 
        $gateway=new PaymentGateway();
        $gateway->name=$banks[ mt_rand(0,(count($banks)-1))];
        $gateway->save();
    }
});
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

Artisan::command('load-items',function(){
    $data=include(__DIR__."\..\data\drugs.php");
    $units=['Sack','kg','litre','Pcs'];
    for ($j=1; $j <= 15; $j++) { 
       
        foreach ($data as $key => $name) {
            $d=mt_rand(100,999);
            $i=new Item();
            $i->title=$name ." (mode.".$j.")";
            $i->sell_price=mt_rand(10,10000);
            $i->cost_price=truncate_decimals($i->sell_price*0.90);
            $i->stock=mt_rand(1,500);
            $i->number=$j.'.'.$d.'.'.$key;
            $i->unit=$units[mt_rand(0,3)];
            $i->posonly=mt_rand(0,1);
            $i->disonly=mt_rand(0,1);
            $i->farmeronly=mt_rand(0,1);
            if($i->disonly==1){
                $i->dis_number=$i->number;
            }
            $i->save();
            echo $i->title ." Saved.\n";
        }
    }
});


