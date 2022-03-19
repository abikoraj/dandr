<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCenterIdPosBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pos_bills', function (Blueprint $table) {
            //
            $table->decimal('points')->default(0);
            $table->unsignedBigInteger('center_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('pos_bills', function (Blueprint $table) {
            //
            $table->dropColumn('points');
            $table->dropColumn('center_id');
        });

    }
}
