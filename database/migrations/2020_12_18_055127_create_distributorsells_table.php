<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorsellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributorsells', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributer_id');
            $table->foreign('distributer_id')->references('id')->on('distributers')->onDelete('cascade');
            $table->integer('date');
            $table->decimal('rate',8,2);
            $table->decimal('qty',8,2);
            $table->decimal('total',8,2);
            $table->decimal('paid',8,2);
            $table->decimal('deu',8,2);
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
        Schema::dropIfExists('distributorsells');
    }
}
