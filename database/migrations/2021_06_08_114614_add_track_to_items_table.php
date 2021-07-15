<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('trackstock')->default(false);
            $table->boolean('trackexpiry')->default(false);
            $table->boolean('sellonline')->default(false);
            $table->boolean('disonly')->default(false);
            $table->boolean('farmeronly')->default(false);
            $table->boolean('posonly')->default(false);

            $table->unsignedInteger('expirydays')->default(0);
            $table->unsignedInteger('minqty')->default(0);

            $table->decimal('dis_price')->nullable();
            $table->string('dis_number')->nullable();


            $table->text('image')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn("trackstock");
            $table->dropColumn("trackexpiry");
            $table->dropColumn("expirydays");
            $table->dropColumn("image");
            $table->dropColumn("description");
            $table->dropColumn("minqty");
            $table->dropColumn("sellonline");
            $table->dropColumn("disonly");
            $table->dropColumn("farmeronly");
            $table->dropColumn("posonly");
            $table->dropColumn("dis_price");
            $table->dropColumn("dis_number");
        });
    }
}
