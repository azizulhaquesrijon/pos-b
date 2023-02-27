<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVatToSaleReturnExchangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_return_exchanges', function (Blueprint $table) {
            $table->decimal('total_return_vat_percent', 12,2)->default(0)->after('total_return_discount_amount');
            $table->decimal('total_return_vat_amount', 12,2)->default(0)->after('total_return_vat_percent');

            $table->decimal('total_exchange_vat_percent', 12,2)->default(0)->after('total_exchange_discount_amount');
            $table->decimal('total_exchange_vat_amount', 12,2)->default(0)->after('total_exchange_vat_percent');
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
            $table->dropColumn('total_return_vat_percent');
            $table->dropColumn('total_return_vat_amount');
            $table->dropColumn('total_exchange_vat_percent');
            $table->dropColumn('total_exchange_vat_amount');
        });
    }
}
