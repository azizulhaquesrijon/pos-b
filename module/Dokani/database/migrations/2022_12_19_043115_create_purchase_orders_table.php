<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('supplier_id');
            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->decimal('payable_amount', 16, 4);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('paid_amount', 16, 4)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->decimal('previous_due', 16, 4)->default(0);

            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->string('note')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('dokan_id')->references('id')->on('users');
            $table->foreign('purchase_id')->references('id')->on('purchases');

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
