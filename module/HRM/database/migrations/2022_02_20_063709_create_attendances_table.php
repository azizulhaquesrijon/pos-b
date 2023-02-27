<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('employee_id');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->string('date')->nullable();
            $table->string('month')->nullable();
            $table->decimal('ot_hour')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->string('check_in_time')->nullable();
            $table->string('check_out_time')->nullable();

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('dokan_id')->references('id')->on('users');
            $table->foreign('employee_id')->references('id')->on('employees');

        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
