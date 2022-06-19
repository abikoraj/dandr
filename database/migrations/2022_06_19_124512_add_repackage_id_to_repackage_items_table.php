<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRepackageIdToRepackageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repackage_items', function (Blueprint $table) {
            $table->unsignedBigInteger('repackage_id');
            $table->foreign('repackage_id')->references('id')->on('repackages');
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
            $table->dropForeign(['repackage_id']);
            $table->dropColumn('repackage_id');
        });
    }
}
