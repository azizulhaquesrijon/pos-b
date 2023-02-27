<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleReturnExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_exchanges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('customer_id');
            $table->string('date');
            $table->decimal('total_return_quantity', 16, 6)->default(0);
            $table->decimal('return_subtotal', 16, 6)->default(0);
            $table->decimal('total_return_discount_percent', 16, 6)->default(0);
            $table->decimal('total_return_discount_amount', 16, 6)->default(0);
            $table->decimal('return_grand_total', 16, 6)->virtualAs('return_subtotal - total_return_discount_amount');
            $table->decimal('total_exchange_quantity', 16, 6)->default(0);
            $table->decimal('total_exchange_cost', 16, 6)->default(0);
            $table->decimal('exchange_subtotal', 16, 6)->default(0);
            $table->decimal('total_exchange_discount_percent', 16, 6)->default(0);
            $table->decimal('total_exchange_discount_amount', 16, 6)->default(0);
            $table->decimal('exchange_grand_total', 16, 6)->virtualAs('exchange_subtotal - total_exchange_discount_amount');
            $table->decimal('subtotal', 16, 6)->virtualAs('return_grand_total - exchange_grand_total');
            $table->decimal('rounding', 16, 6)->default(0);
            $table->decimal('payable_amount', 16, 6)->virtualAs('return_grand_total - exchange_grand_total + rounding');
            $table->decimal('paid_amount', 16, 6)->default(0);
            $table->decimal('due_amount', 16, 6)->default(0);
            $table->decimal('change_amount', 16, 6)->default(0);
            $table->integer('dokan_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();


            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('customer_id')->references('id')->on('customers');
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
        Schema::dropIfExists('sale_return_exchanges');
    }
}
