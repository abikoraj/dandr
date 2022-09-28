<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToChalanDuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chalan_dues', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_chalan_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chalan_dues', function (Blueprint $table) {
            $table->dropColumn('employee_chalan_id');
        });
    }
}
