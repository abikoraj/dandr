<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherBatchIdToBatchFinishedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('batch_finisheds', function (Blueprint $table) {
            $table->unsignedBigInteger('to_batch_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('batch_finisheds', function (Blueprint $table) {
            $table->dropColumn('to_batch_id');
        });
    }
}
