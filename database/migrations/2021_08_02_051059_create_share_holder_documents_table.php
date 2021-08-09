<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareHolderDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_documents', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('document_name',100)->nullable();
            $table->string('document_no',100)->nullable();
            $table->string('issued_from',100)->nullable();
            $table->string('issued_by',100)->nullable();
            $table->integer('issued_date')->nullable();  
            $table->unsignedBigInteger('member_id');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('member_document_images', function (Blueprint $table) {
            $table->id();
            $table->text('path');
            $table->unsignedBigInteger('member_document_id');
            $table->foreign('member_document_id')->references('id')->on('member_documents')->onDelete('cascade');
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
        Schema::dropIfExists('member_documents');
        Schema::dropIfExists('member_document_images');
    }
}
