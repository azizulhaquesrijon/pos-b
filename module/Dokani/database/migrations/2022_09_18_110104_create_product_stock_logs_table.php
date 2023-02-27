<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('product_id')->nullable();

            $table->morphs('sourceable');
            $table->date('date')->nullable();
            $table->string('reference_no')->nullable();
            $table->string('lot')->default('N/A')->nullable();
            $table->date('expiry_at')->nullable()->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('actual_quantity')->nullable();
            $table->string('stock_type')->nullable();
            $table->decimal('purchase_price',16,2)->default(0)->nullable();
            $table->decimal('sale_price',16,2)->default(0)->nullable();
            $table->decimal('total_cost',16,2)->virtualAs('purchase_price * quantity')->nullable();
            $table->decimal('total_amount',16,2)->virtualAs('sale_price * quantity')->nullable();
            $table->timestamps();

            $table->foreign('dokan_id')->references('id')->on('users');
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
        Schema::dropIfExists('product_stock_logs');
    }
}
