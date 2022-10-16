<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatesToFarmersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmers', function (Blueprint $table) {
            //
            $table->boolean('use_custom_rate')->default(false);
            $table->decimal('snf_rate')->nullable();
            $table->decimal('fat_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmers', function (Blueprint $table) {
            //
            $table->dropColumn('use_custom_rate');
            $table->dropColumn('snf_rate');
            $table->dropColumn('fat_rate');

        });
    }
}
