<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChalanduePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chalandue_payments', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount',10,2);
            $table->integer('date');
            $table->string('identifire');
            $table->integer('foreign_key')->nullable();
            $table->unsignedBigInteger('chalan_due_id');
            $table->foreign('chalan_due_id')->references('id')->on('chalan_dues')->onDelete('cascade');
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
        Schema::dropIfExists('chalandue_payments');
    }
}
