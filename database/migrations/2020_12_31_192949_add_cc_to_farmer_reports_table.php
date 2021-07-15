<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCcToFarmerReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmer_reports', function (Blueprint $table) {
            $table->decimal('cc',18,2)->default(0);
            $table->decimal('tc',18,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmer_reports', function (Blueprint $table) {
            $table->dropColumn('cc');
            $table->dropColumn('tc');
        });
    }
}
