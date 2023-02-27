<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAvailableQuantityToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->integer('available_quantity')->nullable()->virtualAs('opening_quantity + purchased_quantity + sold_return_quantity + purchase_exchange_quantity - sold_quantity - wastage_quantity - purchase_return_quantity - sold_exchange_quantity')->after('purchase_return_quantity');
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
