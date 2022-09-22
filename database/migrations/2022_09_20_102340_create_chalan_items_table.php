<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChalanItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chalan_items', function (Blueprint $table) {
            $table->id();
            $table->decimal('qty',8,2);
            $table->decimal('rate',8,2);
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->unsignedBigInteger('employee_chalan_id');
            $table->foreign('employee_chalan_id')->references('id')->on('employee_chalans')->onDelete('cascade');
            $table->unsignedBigInteger('center_id');
            $table->foreign('center_id')->references('id')->on('centers')->onDelete('cascade');
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
        Schema::dropIfExists('chalan_items');
    }
}
