<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariantToBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_variant_id')->nullable();
            $table->foreign('item_variant_id')->references('id')->on('item_variants');
            $table->unsignedBigInteger('item_category_id')->nullable();
            $table->foreign('item_category_id')->references('id')->on('item_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropForeign(['item_variant_id']);
            $table->dropColumn('item_variant_id');
            $table->dropForeign(['item_category_id']);
            $table->dropColumn('item_category_id');
        });
    }
}
