<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepackagingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repackaging_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('repackage_id');
            $table->foreign('repackage_id')->references('id')->on('repackages');
            $table->text('title');
            $table->decimal('amount',12,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repackaging_costs');
    }
}
