<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->morphs('transactionable');
            $table->string('invoice_no')->nullable();
            $table->decimal('amount', 16, 2)->default(0);
            $table->string('balance_type')->nullable();
            $table->date('date')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('dokan_id')->references('id')->on('users');
            $table->foreign('account_id')->references('id')->on('general_accounts');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_flows');
    }
}
