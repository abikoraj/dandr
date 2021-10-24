<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup';

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
        $file = 'backup-' . Carbon::now()->format('Y-m-d-h-i-s');
        $path = public_path('backup');
        File::ensureDirectoryExists($path);

        $filename=$path . DIRECTORY_SEPARATOR .$file;
        $sql = 'mysqldump  --user=' . env('DB_USERNAME') . ' --password=' . env('DB_PASSWORD') . ' --host=' . env('DB_HOST') . ' ' . ' --port=' . env('DB_PORT') . ' ' . env('DB_DATABASE') . ' >"' . $filename . '.sql"';
        system($sql);
        // dd($sql);
    }
}
