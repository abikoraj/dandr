<?php

namespace App\Console\Commands;

use App\Models\CenterStock;
use App\Models\Item;
use Illuminate\Console\Command;

class ImportItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:items';

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
        $file = fopen(public_path('uploads/items.csv'),"r");
        // echo '<pre>';
        while(! feof($file))
        {
            try {
                //code...
                $data=fgetcsv($file);
                $item=new Item();
                $number=$data[0];
                $tempnum=$number;
                $i=0;

                if(Item::where('number',$tempnum)->count()>0){
                    while (Item::where('number',$tempnum)->count()>0) {
                        $tempnum=$number.$i;
                        $i+=1;
                    }
                }

                $item->number = $tempnum;
                $item->title = $data[1];
                $item->cost_price = $data[2];
                $item->sell_price = $data[3];
                $item->stock = $data[5];
                $item->unit = '--';
                $item->wholesale = $data[4];
                $item->trackexpiry=1;
                $item->posonly=1;
                $item->trackstock=1;
                $item->save();


                $center_stock = new CenterStock();
                $center_stock->center_id = env('maincenter',1);
                $center_stock->item_id = $item->id;
                $center_stock->wholesale = $item->wholesale;
                $center_stock->rate = $item->sell_price;
                $center_stock->amount = $item->stock;
                $center_stock->save();

                echo "{$item->title} added.\t\r\n";
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
