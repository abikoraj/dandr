<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedToEmployeeChalansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_chalans', function (Blueprint $table) {
            $table->boolean('approved')->default(false);
            $table->string('approvedBy')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_chalans', function (Blueprint $table) {
            //
            $table->dropColumn('approved');
            $table->dropColumn('approvedBy');

        });
    }
}
