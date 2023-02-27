<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockAdjustInOutQtyAndChangeAvailableQuantityToProductStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('product_stocks', 'available_quantity')){
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->dropColumn('available_quantity');
            });
        }

        Schema::table('product_stocks', function (Blueprint $table) {
            $table->decimal('stock_adjust_in_qty')->default(0)->after('stock_transfer_out_qty');
            $table->decimal('stock_adjust_out_qty')->default(0)->after('stock_adjust_in_qty');
            $table->decimal('available_quantity', 16, 6)
            ->nullable()
            ->virtualAs('opening_quantity + purchased_quantity + sold_return_quantity + purchase_exchange_quantity + stock_transfer_in_qty + production_qty + stock_adjust_in_qty - sold_quantity - wastage_quantity - purchase_return_quantity - stock_transfer_out_qty - production_issue_qty - stock_adjust_out_qty')
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
        if (Schema::hasColumn('product_stocks', 'available_quantity')){
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->dropColumn('available_quantity');
            });
        }
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->dropColumn('stock_adjust_in_qty');
            $table->dropColumn('stock_adjust_out_qty');
            $table->decimal('available_quantity', 16, 6)
            ->nullable()
            ->virtualAs('opening_quantity + purchased_quantity + sold_return_quantity + purchase_exchange_quantity + stock_transfer_in_qty + production_qty - sold_quantity - wastage_quantity - purchase_return_quantity - stock_transfer_out_qty - production_issue_qty')
            ->after('stock_transfer_out_qty');
        });
    }
}
