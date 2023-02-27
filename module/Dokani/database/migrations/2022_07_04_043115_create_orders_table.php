<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('customer_id');

            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->decimal('payable_amount', 16, 4);
            $table->decimal('discount', 8, 4)->default(0);
            $table->decimal('paid_amount', 16, 4)->default(0);
            $table->decimal('due_amount', 10, 4)->default(0);
            $table->decimal('previous_due', 16, 4)->default(0);
            $table->decimal('total_vat', 16, 4)->default(0);

            $table->string('order_by')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('dokan_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
