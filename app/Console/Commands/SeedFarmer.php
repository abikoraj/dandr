<?php

namespace App\Console\Commands;

use App\LedgerManage;
use App\Models\Advance;
use App\Models\Center;
use App\Models\Farmer;
use App\Models\Farmerpayment;
use App\Models\Milkdata;
use App\Models\Sellitem;
use App\Models\Snffat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SeedFarmer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:farmer {u} {cid} {start=290} ';

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
        $u=($this->argument('u'));
        $start=($this->argument('start'))-1;
        $center_id=($this->argument('cid'));
        $center=Center::where('id',$center_id)->first();
        if($center==null){
            echo "No Center Found";
            return 0;
        }
        $now = Carbon::now()->toDateTimeString();
        $items=DB::table('items')->get(['id','title','sell_price']);
        for ($i = 1; $i <= 200; $i++) {
            if($u=="1"){

                $user = new User();
                $user->phone =  9899000000+$i;
                $user->name ="name ".$i;
                $user->address ="address ".$i;;
                $user->role = 1;
                $user->password = bcrypt(12345);
                $user->no = $i;
                $user->save();

                $id = $user->id;
                $farmer = new Farmer();
                $farmer->user_id = $user->id;
                $farmer->center_id =$center_id;
                $farmer->usecc =mt_rand(0,1);
                $farmer->usetc = mt_rand(0,1);
                $farmer->userate =mt_rand(0,1);
                $farmer->rate = (mt_rand(0,1)==1?65:0);
                $farmer->no = $i;
                $farmer->save();
            }else{

                $id = $start+$i;
            }

            $milkdatas = [];
            $snffat = [];
            $advances = [];
            $manager=new LedgerManage($id);
            for ($date = 20790301; $date <= 20790332; $date++) {
                array_push($milkdatas, [
                    'm_amount' => rand(100, 2000) / 100,
                    'e_amount' => rand(100, 2000) / 100,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'date' => $date,
                    'user_id' => $id,
                    'center_id' =>$center_id,
                ]);
                array_push($snffat, [
                    'snf' => rand(500, 1000) / 100,
                    'fat' => rand(300, 800) / 100,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'date' => $date,
                    'user_id' => $id,
                    'center_id' =>$center_id,
                ]);

                if (randomChance()) {
                    $advance=mt_rand(25, 3500);
                    $adv=new Advance();
                    $adv->user_id = $id;
                    $adv->amount = $advance;
                    $adv->date = $date;
                    $adv->save();
                    if(env('acc_system','old')=='old'){
                        $manager->addLedger('Advance to Farmer',1,$adv->amount,$date,'104',$adv->id);
                    }else{
                        $manager->addLedger('Advance to Farmer',2,$adv->amount,$date,'104',$adv->id);
                    }
                }

                if (randomChance()) {
                    $item=$items->random(1)->first();
                    $qty= mt_rand(1,10);
                    $total=$qty*$item->sell_price;
                    $paid=mt_rand(0,$total);
                    $sell_item = new Sellitem();
                    $sell_item->total = $total;
                    $sell_item->qty =$qty;
                    $sell_item->rate = $item->sell_price;
                    $sell_item->due = $total-$paid;
                    $sell_item->paid = $paid;
                    // $user = User::where('no',$request->user_id)->first();
                    $sell_item->user_id = $id;
                    $sell_item->item_id = $item->id;
                    $sell_item->date = $date;
                    $sell_item->save();

                    if(env('acc_system',"old")=="old"){
                        $manager->addLedger($item->title.' ( Rs.'.$sell_item->rate.' x '.$sell_item->qty. ')',1,$sell_item->total,$date,'103',$sell_item->id);
                        if($sell_item->paid>0){
                            $manager->addLedger('Paid amount',2,$sell_item->paid,$date,'106',$sell_item->id);
                        }
                    }else{
                        $manager->addLedger($item->title.' ( Rs.'.$sell_item->rate.' x '.$sell_item->qty. ')',2,$sell_item->total,$date,'103',$sell_item->id);
                        if($sell_item->paid>0){
                            $manager->addLedger('Paid amount',1,$sell_item->paid,$date,'106',$sell_item->id);
                        }
                    }


                }


                if(randomChance(0,5,3)){
                    $farmerPay = new Farmerpayment();
                    $farmerPay->amount = mt_Rand(25,1000);
                    $farmerPay->date = $date;
                    $farmerPay->payment_detail = "detail ".$i;
                    $farmerPay->user_id = $id;
                    $farmerPay->save();

                    if (env('acc_system', 'old') == "old") {

                        $manager->addLedger('Paid by farmer amount', 2, $farmerPay->amount, $date, '107', $farmerPay->id);
                    } else {
                        $manager->addLedger('Paid by farmer amount', 1, $farmerPay->amount, $date, '107', $farmerPay->id);
                    }
                }



            }
            if(randomChance()){
                $manager->addLedger('Opening Balance', mt_rand(1,2), mt_rand(0,2500), 20790301, '101');
           }
            Milkdata::insert($milkdatas);
            Snffat::insert($snffat);
            Advance::insert($advances);
            $milkdatas=[];
            $snffat=[];
            $advances = [];
        }
        return 0;
    }
}
