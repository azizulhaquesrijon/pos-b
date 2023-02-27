<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sale_return_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity')->nullable();
            $table->decimal('price')->nullable();
            $table->text('description')->nullable();
            $table->enum('condition', ['good', 'damaged'])->default('good');
            $table->timestamps();

            $table->foreign('sale_return_id')->references('id')->on('sale_returns');
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
        Schema::dropIfExists('sale_return_details');
    }
}
