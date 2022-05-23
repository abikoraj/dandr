<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufactureProcessItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacture_process_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufacture_process_id');
            $table->foreign('manufacture_process_id')->references('id')->on('manufacture_processes');
            $table->decimal('amount',18,3);
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
        Schema::dropIfExists('manufacture_process_items');
    }
}
