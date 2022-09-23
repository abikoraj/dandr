<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeToChalanSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chalan_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('chalan_item_id')->nullable()->change();
            $table->unsignedBigInteger('employee_chalan_id');
            $table->foreign('employee_chalan_id')->references('id')->on('employee_chalans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chalan_sales', function (Blueprint $table) {
            $table->dropColumn('chalan_item_id');
            $table->dropColumn('employee_chalan_id');
        });
    }
}
