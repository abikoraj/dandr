<?php

namespace App\Console\Commands;

use App\Models\Center;
use App\Models\Distributer;
use App\Models\Farmer;
use App\Models\Ledger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $users=json_decode( file_get_contents(public_path('exports/users.json')));
            // foreach ($users as $key => $user) {
            //     try {
            //         //code...
            //         User::insert((array)$user);
            //         echo $user->name.PHP_EOL;
            //     } catch (\Throwable $th) {
            //         echo $th->getMessage();
            //     }
            // }
            // $centers=json_decode( file_get_contents(public_path('exports/centers.json')));
            // foreach ($centers as $key => $center) {
            //     Center::insert((array)$center);
            //     echo $center->name;
            // }
            // $farmers=json_decode( file_get_contents(public_path('exports/farmers.json')));
            // foreach ($farmers as $key => $farmer) {
            //     try {
            //         Farmer::insert((array)$farmer);
            //         echo $farmer->no.PHP_EOL;
            //         //code...
            //     } catch (\Throwable $th) {
            //         echo $th->getMessage();

            //     }
            // }
            // $distributers=json_decode( file_get_contents(public_path('exports/distributers.json')));
            // foreach ($distributers as $key => $dis) {
            //     try {
            //         Distributer::insert((array)$dis);
            //         echo $dis->rate .PHP_EOL;
            //         //code...
            //     } catch (\Throwable $th) {
            //         echo $th->getMessage();

            //     }
            // }
                $now=Carbon::now()->toDateTimeString();
             $distributers_balance=json_decode( file_get_contents(public_path('exports/distributers_balance.json')));
            foreach ($distributers_balance as $key => $bal) {
                if($bal->amount>0){
                        try {
                            Ledger::insert([
                                "title"=>"Aalya",
                                "amount"=>$bal->amount,
                                "user_id"=>$bal->user_id,
                                "type"=>$bal->type,
                                "year"=>2079,
                                "month"=>4,
                                "session"=>1,
                                "identifire"=>119,
                                "date"=>20790401,
                                "created_at"=>$now,
                                "updated_at"=>$now
                            ]);
                            //code...
                        } catch (\Throwable $th) {
                            echo $th->getMessage();

                        }

                }
            }




        } catch (\Throwable $bth) {
            //bthrow $bth;
            echo $bth->getMessage();
        }
    }
}
