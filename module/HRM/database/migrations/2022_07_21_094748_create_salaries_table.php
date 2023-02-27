<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('employee_id');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->string('date');
            $table->integer('total_days');
            $table->integer('working_days');
            $table->integer('total_present');
            $table->decimal('salary',16,2);
            $table->decimal('payable_salary',16,2);
            $table->integer('total_absent');
            $table->integer('total_off_days');
            $table->integer('total_leave');
            $table->integer('advance');
            $table->integer('ot_hours');
            $table->decimal('ot_amounts',16,2);
            $table->decimal('total_payable_salary',16,2);

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
        Schema::dropIfExists('salaries');
    }
}
