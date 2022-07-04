<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixedAssetCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->decimal('depreciation',5,2);
            $table->timestamps();
        });
        Schema::table('fixed_assets',function(Blueprint $table){
            $table->unsignedBigInteger('fixed_asset_category_id')->nullable();
            $table->foreign('fixed_asset_category_id')->references('id')->on('fixed_asset_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixed_asset_categories');
        Schema::table('fixed_assets',function(Blueprint $table){
            $table->dropForeign(['fixed_asset_category_id']);
            $table->dropColumn('fixed_asset_category_id');
        });
    }
}
