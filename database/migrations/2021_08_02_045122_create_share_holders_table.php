<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareHoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('name_nepali')->nullable();
            $table->text('phone')->nullable();
            $table->text('image')->nullable();
            $table->integer('is_farmer')->default(0);
            $table->integer('is_distributer')->default(0);
            $table->integer('is_customer')->default(0);
            $table->integer('is_supplier')->default(0);
            $table->string('member_no',50)->nullable();
            $table->string('type',50)->default('person');
            $table->string('acc_type',50)->default('normal');
            $table->string('ref_acc',50)->nullable();
            $table->integer('join_date')->nullable();
            //person
            $table->integer('dob')->nullable();
            $table->integer('gender')->nullable();
            //organization
            $table->string('pan_no',50)->nullable();
            $table->string('reg_no',50)->nullable();

            //generational data
            $table->string('father_name',100)->nullable();;
            $table->string('mother_name',100)->nullable();;
            $table->string('spouse_name',100)->nullable();;
            $table->string('grandfather_name',100)->nullable();;

            //permanent address
            $table->string('country',100)->default('nepal');
            $table->string('province',100)->nullable();
            $table->string('district',100)->nullable();
            $table->string('mun',100)->nullable();
            $table->string('ward',100)->nullable();
            $table->string('tole',100)->nullable();
            $table->string('house_no',100)->nullable();
            //current address
            $table->string('c_country',100)->default('nepal');
            $table->string('c_province',100)->nullable();
            $table->string('c_district',100)->nullable();
            $table->string('c_mun',100)->nullable();
            $table->string('c_ward',100)->nullable();
            $table->string('c_tole',100)->nullable();
            $table->string('c_house_no',100)->nullable();

            //Nomiee Detail
            $table->string('n_name')->nullable();
            $table->string('n_phone')->nullable();
            $table->string('n_name_nepali')->nullable();
            $table->string('n_gender')->nullable();
            $table->integer('n_dob')->nullable();
            $table->string('n_relation')->nullable();
            $table->string('n_image')->nullable();
            $table->string('n_country',100)->default('nepal');
            $table->string('n_province',100)->nullable();
            $table->string('n_district',100)->nullable();
            $table->string('n_mun',100)->nullable();
            $table->string('n_ward',100)->nullable();
            $table->string('n_tole',100)->nullable();
            $table->string('n_house_no',100)->nullable();
            //Nomiee document data
            $table->integer('n_document_type')->default(0);
            $table->string('n_document_name',100)->nullable();
            $table->string('n_document_no',100)->nullable();
            $table->string('n_issued_from',100)->nullable();
            $table->string('n_issued_by',100)->nullable();
            $table->integer('n_issued_date')->nullable();
            //Nomiee generational data
            $table->string('n_father_name',100)->nullable();;
            $table->string('n_mother_name',100)->nullable();;
            $table->string('n_spouse_name',100)->nullable();;
            $table->string('n_grandfather_name',100)->nullable();;
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('members');
    }
}
