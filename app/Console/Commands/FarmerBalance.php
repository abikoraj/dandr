<?php

namespace App\Console\Commands;

use App\Models\Ledger;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FarmerBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:farmerbalance';

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
        $now=Carbon::now();
        $farmer_balance = json_decode(file_get_contents(public_path('exports/farmer_balance.json')));
        foreach ($farmer_balance as $key => $bal) {
            if ($bal->amount > 0) {
                try {
                    Ledger::insert([
                        "title" => "Aalya",
                        "amount" => $bal->amount,
                        "user_id" => $bal->user_id,
                        "type" => $bal->type,
                        "year" => 2079,
                        "month" => 4,
                        "session" => 1,
                        "identifire" => 101,
                        "date" => 20790401,
                        "created_at" => $now,
                        "updated_at" => $now
                    ]);
                    //code...
                } catch (\Throwable $th) {
                    echo $th->getMessage();
                }
            }
        }
        return 0;
    }
}
