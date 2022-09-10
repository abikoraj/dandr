<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransportToCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->boolean('use_transport')->default(false);
        });
        Schema::table('farmers', function (Blueprint $table) {
            $table->boolean('use_transport')->default(false);
            $table->decimal('transport')->default(0);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->dropColumn('use_transport');
        });
        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('use_transport');
            $table->dropColumn('transport');
        });
    }
}
