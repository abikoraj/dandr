<?php

namespace Database\Seeders;

use App\Models\Aboutus;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $about = new Aboutus();
        $about->title1 = "WHY CHOOSE US";
        $about->title2 = "OUR MISSION";
        $about->title3 = "WHAT WE DO";
        $about->desc1 = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni accusantium";
        $about->desc2 = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni accusantium";
        $about->desc3 = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni accusantium";
        $about->save();
    }
}
