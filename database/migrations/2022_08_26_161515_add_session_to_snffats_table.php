<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionToSnffatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('snffats', function (Blueprint $table) {
            $table->integer('session')->default(1);
            $table->unsignedBigInteger('milkdata_id')->nullable();
            $table->foreign('milkdata_id')->references('id')->on('milkdatas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('snffats', function (Blueprint $table) {
            $table->dropColumn('session');
            $table->dropForeign(['milkdata_id']);
        });
    }
}
