<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount',8,2);
            $table->integer('date');
            $table->string('payment_detail');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id','d_payemnt')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('distributor_payments');
    }
}
