<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourToManufacturedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manufactured_products', function (Blueprint $table) {
            $table->unsignedInteger('day')->default(0);
            $table->unsignedInteger('hour')->default(0);
            $table->unsignedInteger('minute')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manufactured_products', function (Blueprint $table) {
            $table->dropColumn('day');
            $table->dropColumn('hour');
            $table->dropColumn('minute');
        });
    }
}
