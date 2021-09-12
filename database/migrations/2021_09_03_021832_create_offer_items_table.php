<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');

            $table->foreign('offer_id')->references('id')->on('offers');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('item2_id')->nullable();
            $table->decimal('min',18,3)->default(0);
            $table->decimal('max',18,3)->default(0);
            $table->decimal('buy',18,3)->default(0);
            $table->decimal('flat',18,3)->default(0);
            $table->decimal('percentage',18,3)->default(0);
            $table->decimal('get',18,3)->default(0);

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
        Schema::dropIfExists('offer_items');
    }
}
