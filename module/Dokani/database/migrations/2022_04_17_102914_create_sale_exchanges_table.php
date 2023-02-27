<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_exchanges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('fabric_id')->nullable();
            $table->unsignedBigInteger('size_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->unsignedBigInteger('print_id')->nullable();
            $table->string('lot')->nullable();
            $table->decimal('quantity', 16, 6)->default(0);
            $table->decimal('purchase_price', 16, 6)->default(0);
            $table->decimal('purchase_total_amount', 16, 6)->virtualAs('purchase_price * quantity');
            $table->decimal('sale_price', 16, 6)->default(0);
            $table->decimal('discount_percent', 16, 6)->default(0);
            $table->decimal('discount_amount', 16, 6)->default(0);
            $table->decimal('subtotal', 16, 6)->virtualAs('sale_price * quantity - discount_amount');
            $table->integer('dokan_id')->default(1);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();


            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_exchanges');
    }
}
