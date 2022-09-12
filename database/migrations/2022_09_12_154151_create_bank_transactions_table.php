<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_bank_id')->nullable();
            $table->unsignedBigInteger('to_bank_id')->nullable();
            //1=cash to bank,2= bank to cash,3=bank to bank
            $table->integer('type')->default(1);
            //1=withrawl,2=deposit
            $table->integer('method')->default(1);
            $table->string('number')->nullable();
            $table->text('remarks')->nullable();
            $table->text('by')->nullable();
            $table->decimal('amount',18,2)->default();
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
        Schema::dropIfExists('bank_transactions');
    }
}
