<?php

namespace App\Console\Commands;

use App\AccountManager;
use App\Models\Account;
use App\Models\FiscalYear;
use Illuminate\Console\Command;

class InitiateAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:accounts {fy}';

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
        $fy=FiscalYear::where('name',$this->argument('fy'))->first();
        if($fy==null){
            echo "Cannot Find Fiscal Year";
        }

        foreach (AccountManager::assets  as $key => $asset) {
            if(Account::where('fiscal_year_id',$fy->id)->where('identifire',"1.".$key)->count()>0){
                echo $asset." Account Already Exists". PHP_EOL ;

            }else{
                $account=new Account();
                $account->name=$asset;
                $account->fiscal_year_id=$fy->id;
                $account->type=1;
                $account->identifire="1.".$key;
                $account->save();
                echo $asset." Account Created Sucessfully for Fiscal Year ". $fy->name . PHP_EOL ;
            }
        }
        foreach (AccountManager::libilities  as $key => $libility) {
            if(Account::where('fiscal_year_id',$fy->id)->where('identifire',"2.".$key)->count()>0){
                echo $libility." Account Already Exists". PHP_EOL ;
            }else{
                $account=new Account();
                $account->name=$libility;
                $account->fiscal_year_id=$fy->id;
                $account->type=2;
                $account->identifire="2.".$key;
                $account->save();
                echo $libility." Account Created Sucessfully for Fiscal Year ". $fy->name . PHP_EOL ;
            }
        }

        return 0;
    }
}
