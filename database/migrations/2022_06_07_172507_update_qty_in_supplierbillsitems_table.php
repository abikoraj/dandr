<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateQtyInSupplierbillsitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supplierbillitems', function (Blueprint $table) {
            //
            $table->decimal('qty',18,3)->change();
            $table->decimal('remaning',18,3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supplierbillitems', function (Blueprint $table) {
            //
            $table->decimal('qty',8,2)->change();
            $table->decimal('remaning',8,2)->change();


        });
    }
}
