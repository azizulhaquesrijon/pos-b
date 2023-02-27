<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeManyColumnsToSaleExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_exchanges', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropColumn('lot');
            $table->dropColumn('purchase_total_amount');
            $table->dropColumn('subtotal');
            $table->renameColumn('quantity','total_quantity');
            $table->renameColumn('purchase_price', 'total_purchase_price');
            $table->renameColumn('sale_price', 'total_sale_price');
            $table->string('invoice_no')->nullable();
            $table->unsignedBigInteger('sale_return_exchange_id');

            $table->foreign('sale_return_exchange_id')->references('id')->on('sale_return_exchanges');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_exchanges', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('lot');
            $table->renameColumn('total_quantity', 'quantity');
            $table->renameColumn('total_purchase_price', 'purchase_price');
            $table->decimal('purchase_total_amount',16,2);
            $table->renameColumn('total_sale_price', 'sale_price');
            $table->decimal('subtotal', 16,6)->default(0);
            $table->dropColumn('invoice_no');

            $table->dropForeign(['sale_return_exchange_id']);
            $table->dropColumn('sale_return_exchange_id');
        });

    }
}
