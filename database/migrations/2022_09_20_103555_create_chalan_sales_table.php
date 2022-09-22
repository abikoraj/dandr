<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChalanSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chalan_sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total',8,2);
            $table->decimal('qty',8,2);
            $table->decimal('rate',8,2);
            $table->decimal('paid',8,2);
            $table->decimal('due',8,2);
            $table->date('date');
            $table->unsignedBigInteger('chalan_item_id');
            $table->foreign('chalan_item_id')->references('id')->on('chalan_items')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
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
        Schema::dropIfExists('chalan_sales');
    }
}
