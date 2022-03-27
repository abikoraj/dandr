<?php

namespace App\Console\Commands;

use App\Models\Sms;
use App\sms\Aakash;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms {num=100}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send SMS';

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
        $sent = [];
        $blocked = [];
        $msgs = DB::table('sms')->where('sent', 0)->where('pulled', 0)->take($this->argument('num'))->get();
        if ($msgs->count() > 0) {
            Sms::where('id', '<=', $msgs->max('id'))->update(['pulled' => 1]);
            $this->provider = new Aakash();
            foreach ($msgs as $key => $msg) {
                echo "Sending sms to ".$msg->to." \r\n";
                // dd($msg);

                try {
                    if ($this->provider->sendMessage($msg->to,$msg->msg)) {
                        array_push($sent, $msg->id);
                    } else {
                        array_push($blocked, $msg->id);
                    }
                } catch (\Throwable $th) {
                    array_push($blocked, $msg->id);
                    echo "Error: ".$th->getMessage();
                }
            }
            if(count($blocked)>0){
                DB::table('sms')->whereIn('id', $blocked)->update([
                    'sent'=>0,
                    'pulled'=>0
                ]);
            }
            if(count($sent)>0){
                DB::table('sms')->whereIn('id', $sent)->update([
                    'sent'=>1,
                    'pulled'=>1
                ]);
            }

        }
    }
}
