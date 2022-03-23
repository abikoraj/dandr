<?php

namespace App\Console\Commands;

use App\Models\Ledger;
use App\Models\User;
use App\NepaliDate;
use Illuminate\Console\Command;

class ImportSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:supplier';

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
        $file = fopen(public_path('uploads/vendors.csv'),"r");
        // echo '<pre>';
        while(! feof($file))
        {
            try {
                //code...
                $data=fgetcsv($file);


                $user = new User();
                $user->phone = $data[2];
                $user->name = $data[1];
                $user->address = $data[3];
                $user->role = 3;
                $user->password = bcrypt($user->phone);
                $user->save();

                $advance=floatval($data[5]);
                $due=floatval($data[4]);
                if($advance!=0 || $due!=0){

                    $l=new Ledger();
                    $nepalidate = new NepaliDate(20781209);
                    $l->date = 20781209;
                    $l->identifire = '128';

                    $l->year = $nepalidate->year;
                    $l->month = $nepalidate->month;
                    $l->session = $nepalidate->session;

                    $l->user_id=$user->id;
                    $l->title="Opening Balance";
                    if($advance>0){
                        $l->type=2;
                        $l->amount=$advance;
                    }

                    if($due>0){
                        $l->type=1;
                        $l->amount=$due;
                    }
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
