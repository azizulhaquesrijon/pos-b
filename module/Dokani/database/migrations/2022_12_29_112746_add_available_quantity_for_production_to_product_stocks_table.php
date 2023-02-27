<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvailableQuantityForProductionToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('available_quantity', 16, 6)
            ->nullable()
            ->virtualAs('opening_quantity + purchased_quantity + sold_return_quantity + stock_transfer_in_qty + production_qty - sold_quantity - wastage_quantity - purchase_return_quantity - stock_transfer_out_qty - production_issue_qty')
            ->after('stock_transfer_out_qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            //
        });
    }
}
