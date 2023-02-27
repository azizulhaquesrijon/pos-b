<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_exchanges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchase_return_exchange_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('price', 16, 4);
            $table->decimal('quantity')->default(1);
            $table->decimal('total_amount');
            $table->text('lot')->nullable();
            $table->date('expire_at')->nullable();

            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products');
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
        Schema::dropIfExists('purchase_exchanges');
    }
}
