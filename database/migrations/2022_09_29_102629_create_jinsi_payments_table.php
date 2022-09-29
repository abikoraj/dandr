<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJinsiPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jinsi_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('date');
            $table->text('remarks')->nullable();
            $table->decimal('amount');
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
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
        Schema::dropIfExists('jinsi_payments');
    }
}
