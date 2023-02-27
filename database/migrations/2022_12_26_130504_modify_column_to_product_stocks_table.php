<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyColumnToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::table('product_stocks', function (Blueprint $table) {
                $table->dropColumn('stock_transfer_qty');
    
                $table->decimal('stock_transfer_in_qty',10,2)->default(0)->after('purchase_return_quantity');
                $table->decimal('stock_transfer_out_qty',10,2)->default(0)->after('stock_transfer_in_qty');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
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
