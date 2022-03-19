<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCancelToStockOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            //
            $table->boolean('canceled')->default(false);

        });
        Schema::table('stock_out_items', function (Blueprint $table) {
            //
            $table->boolean('canceled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_outs', function (Blueprint $table) {
            //
            $table->dropColumn('canceled');
        });
        Schema::table('stock_out_items', function (Blueprint $table) {
            //
            $table->dropColumn('canceled');
        });
    }
}
