<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConversionIdToPosBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos_bill_items', function (Blueprint $table) {
            $table->unsignedBigInteger('conversion_id')->nullable();
            $table->foreign('conversion_id')->references('id')->on('conversions');
            $table->decimal('conversion_qty',18,3)->default(0)->nullable();
            $table->decimal('conversion_rate',18,2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_bill_items', function (Blueprint $table) {
            $table->dropForeign(['conversion_id']);
            $table->dropColumn('conversion_id');
            $table->dropColumn('conversion_qty');
            $table->dropColumn('conversion_rate');
        });
    }
}
