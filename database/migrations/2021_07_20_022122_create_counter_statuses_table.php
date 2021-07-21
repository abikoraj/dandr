<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCounterStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counter_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('date');
            $table->boolean('active')->default(0);
            $table->unsignedInteger('status')->default(0);
            $table->decimal('request',18,2)->default(0);
            $table->decimal('opening',18,2)->default(0);
            $table->decimal('current',18,2)->default(0);
            $table->decimal('closing',18,2)->default(0);
            $table->unsignedBigInteger('counter_id');
            $table->foreign('counter_id')->references('id')->on('counters')->onDelete('cascade');
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
        Schema::dropIfExists('counter_statuses');
    }
}
