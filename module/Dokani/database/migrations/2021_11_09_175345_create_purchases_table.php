<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('dokan_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();

            $table->string('invoice_no')->nullable();
            $table->date('date')->nullable();
            $table->string('chalan_id')->nullable()->comment('attachment');
            $table->decimal('payable_amount', 16, 4);
            $table->decimal('discount', 8, 4)->default(0);
            $table->decimal('paid_amount', 16, 4)->default(0);
            $table->decimal('due_amount', 10, 4)->default(0);

            $table->string('received_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
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
        Schema::dropIfExists('purchases');
    }
}
