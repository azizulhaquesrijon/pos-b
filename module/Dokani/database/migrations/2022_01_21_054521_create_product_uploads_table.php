<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_uploads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dokan_id')->nullable();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('unit')->nullable();
            $table->string('barcode')->nullable();
            $table->string('buy_price');
            $table->string('sell_price');
            $table->string('openingQty')->nullable();
            $table->string('alertQty')->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_uploads');
    }
}
