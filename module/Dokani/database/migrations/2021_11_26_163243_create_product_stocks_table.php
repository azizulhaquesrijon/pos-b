<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('sale_return_exchange_id')->nullable();
            $table->bigInteger('opening_quantity')->default('0');
            $table->bigInteger('available_quantity')->default('0');
            $table->bigInteger('purchased_quantity')->default('0');
            $table->bigInteger('sold_quantity')->default('0');
            $table->bigInteger('wastage_quantity')->default('0');
            $table->bigInteger('sold_return_quantity')->default('0');
            $table->bigInteger('purchase_return_quantity')->default('0');

            $table->timestamps();


            $table->foreign('product_id')->references('id')->on('products');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stocks');
    }
}
