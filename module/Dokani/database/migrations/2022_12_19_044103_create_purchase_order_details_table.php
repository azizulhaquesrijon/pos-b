<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('purchase_order_details')) {
            Schema::create('purchase_order_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('purchase_order_id');
                $table->unsignedBigInteger('product_id');
                //$table->unsignedBigInteger('variation_id')->nullable();
                $table->decimal('price', 16, 4);
                $table->decimal('quantity')->default(1);
                $table->decimal('total_amount');
                $table->date('expire_at')->nullable();
                $table->timestamps();

                $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
                $table->foreign('product_id')->references('id')->on('products');
                //$table->foreign('variation_id')->references('id')->on('product_variations'); 
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
