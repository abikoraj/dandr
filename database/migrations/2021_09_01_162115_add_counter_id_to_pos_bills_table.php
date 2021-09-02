<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCounterIdToPosBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_bills', function (Blueprint $table) {
            $table->unsignedBigInteger('counter_id')->nullable();
            $table->text('counter_name')->nullable();
            $table->foreign('counter_id')->references('id')->on('counters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_bills', function (Blueprint $table) {
            $table->dropForeign(['counter_id']);
            $table->dropColumn('counter_id');
        });
    }
}
