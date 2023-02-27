<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAccountIdSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->decimal('account_amount',16,4)->default(0)->after('refer_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->decimal('account_amount',16,4)->default(0)->after('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
