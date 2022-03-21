<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Console\Command;

class ImportCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customer';

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
        $file = fopen(public_path('uploads/customers.csv'), "r");
        // echo '<pre>';
        while (!feof($file)) {
            try {
                //code...
                $data = fgetcsv($file);


                $user = new User();
                if ($data[2] != '') {

                    $user->phone = $data[2];
                } else {
                    $user->phone = '98';
                }
                $user->name = $data[0];
                $user->address = $data[1];
                $user->role = 5;
                $user->password = bcrypt($user->phone);
                $user->save();

                $customer=new Customer();
                $customer->user_id=$user->id;
                $customer->center_id=env('maincenter',1);
                $customer->foreign_id=$user->id;
                $customer->save();

                $due = floatval($data[3]);
                if ($due > 0) {

                    $l = new Ledger();
                    $nepalidate = new NepaliDate(20781208);
                    $l->date = 20781208;
                    $l->identifire = '134';
                    $l->year = $nepalidate->year;
                    $l->month = $nepalidate->month;
                    $l->session = $nepalidate->session;
                    $l->user_id = $user->id;
                    $l->title = "Opening Balance";
                    $l->type = 1;
                    $l->amount = $due;
                    $l->save();
                }


                echo "{$user->name} added.\t\r\n";
            } catch (\Throwable $th) {
                //throw $th;
                echo $th->getMessage();
            }
        }
        // echo '</pre>';
        fclose($file);
        return 0;
    }
}
