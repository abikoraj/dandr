<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepackageItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repackage_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_item_id');
            $table->unsignedBigInteger('to_item_id');
            $table->decimal('from_amount',18,3);
            $table->decimal('to_amount',18,3);
            $table->decimal('from_conversion_id',18,3);
            $table->decimal('to_conversion_id',18,3);
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
        Schema::dropIfExists('repackage_items');
    }
}
