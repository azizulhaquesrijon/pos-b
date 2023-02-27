<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->unsignedBigInteger('dokan_id')->nullable()->after('id');
            $table->string('lot')->nullable()->default('N/A')->after('product_id');
            $table->date('expiry_at')->nullable()->after('lot');
            $table->decimal('stock_in_value',16,2)->nullable()->default(0)->after('purchase_return_quantity');
            $table->decimal('stock_out_value',16,2)->nullable()->default(0)->after('stock_in_value');
            $table->decimal('total_value')->virtualAs('stock_in_value - stock_out_value')->nullable()->after('stock_out_value'); // virtualAs

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
        Schema::table('product_stocks', function (Blueprint $table) {
            //
        });
    }
}
