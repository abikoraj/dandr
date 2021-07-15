<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->decimal('rate',8,2);
            $table->decimal('qty',8,2);
            $table->unsignedBigInteger('product_purchase_id');
            $table->foreign('product_purchase_id')->references('id')->on('product_purchases')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('product_purchase_items');
    }
}
