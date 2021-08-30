<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_bill_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('pos_bill_id');
            $table->foreign('pos_bill_id')->references('id')->on('pos_bills');
            $table->string('name',100)->nullable();
            $table->decimal('rate',12,2)->default(0);
            $table->decimal('qty',12,2)->default(0);
            $table->decimal('amount',12,2)->default(0);
            $table->decimal('total',12,2)->default(0);
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
        Schema::dropIfExists('pos_bill_items');
    }
}
