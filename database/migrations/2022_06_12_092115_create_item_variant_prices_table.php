<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemVariantPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_variant_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_variant_id');
            $table->foreign('item_variant_id')->references('id')->on('item_variants');
            $table->unsignedBigInteger('center_id');
            $table->foreign('center_id')->references('id')->on('centers');
            $table->decimal('wholesale',18,2)->default(0);
            $table->decimal('price',18,2)->default(0);
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
        Schema::dropIfExists('item_variant_prices');
    }
}
