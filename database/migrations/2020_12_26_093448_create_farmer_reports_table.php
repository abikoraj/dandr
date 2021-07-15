<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmerReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmer_reports', function (Blueprint $table) {
            $table->id();
            $table->decimal('milk',8,2);
            $table->decimal('snf',8,2);
            $table->decimal('fat',8,2);
            $table->decimal('rate',8,2);
            $table->decimal('total',8,2);
            $table->decimal('due',8,2);
            $table->decimal('prevdue',8,2);
            $table->decimal('advance',8,2);
            $table->decimal('nettotal',8,2);
            $table->decimal('balance',8,2);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('farmer_reports');
    }
}
