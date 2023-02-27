<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleExchangeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::create('sale_exchange_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sale_exchange_id');
                $table->unsignedBigInteger('product_id');
                $table->string('lot')->nullable();
                $table->decimal('previous_qty', 16,6)->default(0);
                $table->decimal('quantity', 16,6)->default(0);
                $table->decimal('unit_price', 16,6)->default(0);
                $table->decimal('sub_total', 16,6)->virtualAs('quantity * unit_price');
                $table->string('comment')->nullable();
                $table->timestamps();

                $table->foreign('sale_exchange_id')->references('id')->on('sale_exchanges');
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
        Schema::dropIfExists('sale_exchange_details');
    }
}
