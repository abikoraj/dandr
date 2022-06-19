<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveConversionIdFromRepackageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repackage_items', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('from_conversion_id')->nullable()->change();
            $table->unsignedBigInteger('to_conversion_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repackage_items', function (Blueprint $table) {
            //
        });
    }
}
