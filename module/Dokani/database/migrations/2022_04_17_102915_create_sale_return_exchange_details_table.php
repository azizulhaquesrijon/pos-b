<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnExchangeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_exchange_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_return_exchange_id');
            $table->unsignedBigInteger('sale_detail_id');
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->unsignedBigInteger('sale_exchange_id')->nullable();
            $table->timestamps();


            $table->foreign('sale_return_exchange_id')->references('id')->on('sale_return_exchanges');
            $table->foreign('sale_detail_id')->references('id')->on('sale_details');
            $table->foreign('sale_return_id')->references('id')->on('sale_returns');
            $table->foreign('sale_exchange_id')->references('id')->on('sale_exchanges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_return_exchange_details');
    }
}
