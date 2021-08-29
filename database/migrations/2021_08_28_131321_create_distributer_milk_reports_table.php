<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributerMilkReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributer_milk_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributer_id');
            $table->foreign('distributer_id')->references('id')->on('distributers')->onDelete('cascade');
            $table->decimal('milk',8,2);
            $table->decimal('snf',8,2);
            $table->decimal('fat',8,2);
            $table->decimal('rate',8,2);
            $table->decimal('is_fixed',8,2);
            $table->decimal('total',8,2);
            $table->integer('year');
            $table->integer('month');
            $table->integer('session');
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
        Schema::dropIfExists('distributer_milk_reports');
    }
}
