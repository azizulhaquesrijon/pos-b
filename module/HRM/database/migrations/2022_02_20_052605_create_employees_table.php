<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('dokan_id');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('mobile');
            $table->string('address')->nullable();
            $table->string('father_name')->nullable();
            $table->string('image')->nullable();
            $table->string('nid_image')->nullable();
            $table->decimal('salary', 8, 3)->nullable();
            $table->string('joining_date')->nullable();
            $table->tinyInteger('is_user')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1);

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dokan_id')->references('id')->on('users');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
