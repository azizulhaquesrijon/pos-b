<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDamageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_damage_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_damage_id');
            $table->unsignedBigInteger('product_id');
            $table->string('lot')->nullable();
            $table->string('condition')->default('Damaged')->comment('Damaged, Expired');

            $table->decimal('quantity', 16, 6)->nullable()->default(0);
            $table->decimal('purchase_price', 16, 6)->nullable()->default(0);
            $table->decimal('purchase_total_amount', 16, 6)->nullable()->default(0);
            $table->decimal('sale_price', 16, 6)->nullable()->default(0);
            $table->decimal('discount_percent', 16, 6)->nullable()->default(0);
            $table->decimal('discount_amount', 16, 6)->nullable()->default(0);
            $table->decimal('subtotal', 16, 6)->virtualAs('sale_price * quantity - discount_amount');

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('product_damage_id')->references('id')->on('product_damages');
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
        Schema::dropIfExists('product_damage_details');
    }
}
