<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToMilkDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('milk_days', function (Blueprint $table) {
            $table->decimal('amount',18,2)->default(0);
            $table->integer('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('milk_days', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('date');
        });
    }
}
