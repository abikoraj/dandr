<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosHoldBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_hold_bills', function (Blueprint $table) {
            $table->id();
            $table->text('data');
            $table->boolean('used')->default(false);
            $table->unsignedBigInteger('counter_id')->nullable();
            $table->text('customer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_hold_bills');
    }
}
