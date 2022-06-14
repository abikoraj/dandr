<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStockFromItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            //
            $table->decimal('stock',18,5)->change();
        });
        Schema::table('center_stocks', function (Blueprint $table) {
            //
            $table->decimal('amount',18,5)->change();
        });
        Schema::table('pos_bill_items', function (Blueprint $table) {
            //
            $table->decimal('qty',18,5)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
            $table->decimal('stock',18,2)->change();

        });
        Schema::table('center_stocks', function (Blueprint $table) {
            //
            $table->decimal('amount',18,2)->change();

        });
        Schema::table('pos_bill_items', function (Blueprint $table) {
            //
            $table->decimal('qty',12,2)->change();
        });
    }
}
