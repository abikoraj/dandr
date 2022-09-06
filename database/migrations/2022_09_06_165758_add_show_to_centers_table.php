<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowToCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centers', function (Blueprint $table) {
            $table->boolean('show_ts')->default(false);
            $table->boolean('show_cc')->default(false);
            $table->decimal('protsahan')->default(0);
            $table->boolean('use_protsahan')->default(false);
        });

        Schema::table('farmers', function (Blueprint $table) {
            $table->decimal('protsahan')->default(0);
            $table->boolean('use_protsahan')->default(false);
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
            $table->dropColumn('show_ts');
            $table->dropColumn('show_cc');
            $table->dropColumn('protsahan');
            $table->dropColumn('use_protsahan');
        });

        Schema::table('farmers', function (Blueprint $table) {
            $table->dropColumn('protsahan')->default(0);
            $table->dropColumn('use_protsahan')->default(false);
        });
    }
}
