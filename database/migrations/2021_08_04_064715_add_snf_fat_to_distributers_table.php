<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnfFatToDistributersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distributers', function (Blueprint $table) {
            $table->decimal('snf_rate',18,4)->default(0);
            $table->decimal('fat_rate',18,4)->default(0);
            $table->decimal('added_rate',18,4)->default(0);
            $table->decimal('fixed_rate',18,4)->default(0);
            $table->integer('is_fixed')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distributers', function (Blueprint $table) {
            $table->dropColumn('snf_rate');
            $table->dropColumn('fat_rate');
            $table->dropColumn('added_rate');
            $table->dropColumn('fixed_rate');
            $table->dropColumn('is_fixed');
        });
    }
}
