<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class ImportEmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:emp';

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
        $emps=json_decode( file_get_contents(public_path('exports/employees.json')));
        foreach ($emps as $key => $emp) {
            try {
                Employee::insert((array)$emp);
                echo $emp->no.PHP_EOL;
                //code...
            } catch (\Throwable $th) {
                echo $th->getMessage();

            }
        }
        return 0;
    }
}
