<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditNoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('credit_note_id');
            $table->foreign('credit_note_id')->references('id')->on('credit_notes');
            
            $table->string('name',100)->nullable();
            $table->decimal('rate',12,2)->default(0);
            $table->decimal('discount',12,2)->default(0);
            $table->decimal('taxable',12,2)->default(0);
            $table->decimal('tax',12,2)->default(0);
            $table->decimal('amount',12,2)->default(0);
            $table->decimal('total',12,2)->default(0);
            $table->timestamps();
        });
        Schema::table('pos_bill_items', function (Blueprint $table) {
            $table->decimal('discount',12,2)->default(0);
            $table->decimal('taxable',12,2)->default(0);
            $table->decimal('tax',12,2)->default(0);
            $table->boolean('use_tax')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_note_items');
        Schema::table('pos_bill_items', function (Blueprint $table) {
            $table->dropColumn('discount');
            $table->dropColumn('taxable');
            $table->dropColumn('tax');
            $table->dropColumn('use_tax');
        });
    }
}
