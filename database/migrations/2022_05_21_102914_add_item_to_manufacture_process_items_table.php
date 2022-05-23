<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemToManufactureProcessItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufacture_process_items', function (Blueprint $table) {
            $table->unsignedBigInteger('manufactured_product_item_id');
            $table->foreign('manufactured_product_item_id')->references('id')->on('manufactured_product_items');
            $table->unsignedBigInteger('center_id')->nullable();
            $table->foreign('center_id')->references('id')->on('centers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufacture_process_items', function (Blueprint $table) {
            $table->dropForeign(['manufactured_product_item_id']);
            $table->dropColumn('manufactured_product_item_id');
            $table->dropForeign(['center_id']);
            $table->dropColumn('center_id');
        });
    }
}
