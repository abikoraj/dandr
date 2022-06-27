<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('date');
            $table->text('title')->nullable();
            $table->text('payment_detail')->nullable();
            $table->text('received_by')->nullable();
            $table->decimal('amount',12,2);
            $table->unsignedBigInteger('extra_income_category_id');
            $table->foreign('extra_income_category_id')->references('id')->on('extra_income_categories');
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
        Schema::dropIfExists('extra_incomes');
    }
}
