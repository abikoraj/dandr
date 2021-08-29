<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributersnffatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributersnffats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distributer_id');
            $table->foreign('distributer_id')->references('id')->on('distributers')->onDelete('cascade');
            $table->integer('date')->nullable();
            $table->decimal('snf',18,2)->default(0);
            $table->decimal('fat',18,2)->default(0);
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
        Schema::dropIfExists('distributersnffats');
    }
}
