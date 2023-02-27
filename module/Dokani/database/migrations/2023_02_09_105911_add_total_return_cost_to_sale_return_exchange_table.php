<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalReturnCostToSaleReturnExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_return_exchanges', function (Blueprint $table) {
            $table->decimal('total_return_cost', 16,2)->default(0)->after('total_return_quantity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_return_exchanges', function (Blueprint $table) {
            $table->dropColumn('total_return_cost');
        });
    }
}
