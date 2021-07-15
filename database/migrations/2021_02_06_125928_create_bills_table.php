<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('billno',100)->nullable();
            $table->string('name',100)->nullable();
            $table->string('address',100)->nullable();
            $table->string('phone',100)->nullable();
            $table->string('panvat',100)->nullable();
            $table->string('printed By',100)->nullable();
            $table->decimal('grandtotal',12,2)->default(0);
            $table->decimal('paid',12,2)->default(0);
            $table->decimal('due',12,2)->default(0);
            $table->integer('date')->nullable();
            $table->decimal('return',12,2)->default(0);
            $table->boolean('isprinted')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
