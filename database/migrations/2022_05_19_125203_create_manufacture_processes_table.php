<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufactureProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacture_processes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manufactured_product_id');
            $table->foreign('manufactured_product_id')->references('id')->on('manufactured_products');
            $table->unsignedBigInteger('conversion_id');
            $table->foreign('conversion_id')->references('id')->on('conversions');
            $table->decimal('expected',18,3)->default(1);
            $table->decimal('actual',18,3)->default(1);
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->dateTime('expected_end')->nullable();
            $table->unsignedBigInteger('stage');
            $table->boolean('finished')->default(false);
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
        Schema::dropIfExists('manufacture_processes');
    }
}
