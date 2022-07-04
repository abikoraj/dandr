<?php

namespace App\Console\Commands;

use App\Models\Supplier as ModelsSupplier;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class supplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:supplier';

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
        $users=DB::table('users')->where('role',3)->get(['id','name']);
        foreach ($users as $key => $user) {
            if(DB::table('suppliers')->where('user_id',$user->id)->count()==0){
                $supplier=new ModelsSupplier();
                $supplier->user_id=$user->id;
                $supplier->save();
                echo $user->name." added to supplier ". PHP_EOL;
            }
        }
        return 0;
    }
}
