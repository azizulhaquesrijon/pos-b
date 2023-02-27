<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('purchase_return_exchange_id');
            $table->unsignedBigInteger('purchase_detail_id');
            $table->integer('purchase_price')->nullable();
            $table->integer('quantity');
            $table->decimal('subtotal', 16, 4)->default(0);
            $table->string('return_type');

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('purchase_detail_id')->references('id')->on('purchase_details');
            $table->foreign('purchase_return_exchange_id')->references('id')->on('purchase_return_exchanges');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_returns');
    }
}
