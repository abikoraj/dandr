<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributerMilksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributer_milks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributer_id');
            $table->foreign('distributer_id')->references('id')->on('distributers')->onDelete('cascade');
            $table->integer('date')->nullable();
            $table->decimal('amount',18,2)->default(0);
            $table->integer('type')->default(0);
            $table->integer('session')->default(1);
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
        Schema::dropIfExists('distributer_milks');
    }
}
