<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('date')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('account_type_id');
            $table->decimal('total_amount');
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
        Schema::dropIfExists('voucher_payments');
    }
}
