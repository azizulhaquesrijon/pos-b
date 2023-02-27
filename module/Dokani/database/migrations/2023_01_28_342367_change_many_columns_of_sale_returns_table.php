<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeManyColumnsOfSaleReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('sale_returns', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('dokan_id');
                $table->unsignedBigInteger('customer_id')->after('branch_id')->nullable();

                $table->dropColumn('product_id');
                $table->unsignedBigInteger('sale_return_exchange_id')->nullable();
                $table->string('invoice_no');
                $table->renameColumn('sale_price', 'total_amount')->default(0)->change();
                $table->decimal('quantity',16,2)->default(0)->change();
                $table->decimal('subtotal', 16, 4)->default(0)->change();
                $table->string('return_type')->nullable()->change();

                $table->dropForeign(['sale_detail_id']);
                $table->dropColumn('sale_detail_id');

                $table->foreign('branch_id')->references('id')->on('branches');
                $table->foreign('customer_id')->references('id')->on('customers');
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
            Schema::table('sale_returns', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn('branch_id');

                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');

                $table->dropForeign(['sale_return_exchange_id']);
                $table->dropColumn('sale_return_exchange_id');
                $table->unsignedBigInteger('product_id');

                $table->dropColumn('invoice_no');
                $table->renameColumn('total_amount', 'sale_price')->default(0)->change();
                $table->decimal('quantity',16,2)->default(0)->change();
                $table->decimal('subtotal', 16, 4)->default(0)->change();
                $table->string('return_type')->nullable()->change();

                $table->unsignedBigInteger('sale_detail_id');
                $table->foreign('sale_detail_id')->references('id')->on('sale_details');
            });

    }
}
