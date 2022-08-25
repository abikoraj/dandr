<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {phone} {password}' ;

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
        $phone = $this->argument('phone');
        $password = $this->argument('password');
        $user=new User();
        $user->name='Admin';
        $user->phone=$phone;
        $user->password=bcrypt($password);
        $user->role=0;
        $user->address="---------";
        $user->save();
        echo "user created";
        return 1;
    }
}
