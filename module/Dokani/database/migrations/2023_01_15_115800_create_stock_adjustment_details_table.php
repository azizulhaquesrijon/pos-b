<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_adjustment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('stock_adjustment_id');
            $table->unsignedBigInteger('product_id');
            $table->string('adjustment_type')->comment('In,Out');
            $table->decimal('previous_qty', 16,6)->default(0);
            $table->decimal('quantity', 16,6)->default(0);
            $table->decimal('unit_price', 16,6)->default(0);
            $table->decimal('sub_total', 16,6)->virtualAs('quantity * unit_price');
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('dokan_id')->references('id')->on('users');
            $table->foreign('stock_adjustment_id')->references('id')->on('stock_adjustments');
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
        Schema::dropIfExists('stock_adjustment_details');
    }
}
