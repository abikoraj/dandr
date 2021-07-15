<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('cr',8,2)->nullable();
            $table->decimal('dr',8,2)->nullable();
            $table->decimal('amount');
            $table->integer('date');
            $table->integer('identifire');
            $table->integer('foreign_key')->nullable();
            $table->string('year');
            $table->string('month');
            $table->string('session');
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
        Schema::dropIfExists('ledgers');
    }
}
