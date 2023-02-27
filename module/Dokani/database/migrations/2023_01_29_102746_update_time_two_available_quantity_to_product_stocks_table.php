<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTimeTwoAvailableQuantityToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('product_stocks', 'available_quantity')){
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->dropColumn('available_quantity');
            });
        }
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('available_quantity', 16, 6)->nullable()
            ->virtualAs('opening_quantity + purchased_quantity + sold_return_quantity + purchase_exchange_quantity + stock_transfer_in_qty + production_qty - sold_quantity - wastage_quantity - sold_exchange_quantity - purchase_return_quantity - stock_transfer_out_qty - production_issue_qty')
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
