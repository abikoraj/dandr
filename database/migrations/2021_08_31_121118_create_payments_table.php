<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->default(0);
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')->references('id')->on('banks');
            $table->unsignedBigInteger('payment_gateway_id')->nullable();
            $table->foreign('payment_gateway_id')->references('id')->on('payment_gateways');
            $table->text('bank_name')->nullable();
            $table->integer('bank_txn_type')->nullable();
            $table->text('cardno')->nullable();
            $table->text('chequeno')->nullable();
            $table->text('txn_no')->nullable();
            $table->text('txn_code')->nullable();
            $table->text('image')->nullable();
            $table->integer('identifire')->default(101);
            $table->unsignedBigInteger('foreign_key')->nullable();
            $table->integer('direction')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('payments');
    }
}
