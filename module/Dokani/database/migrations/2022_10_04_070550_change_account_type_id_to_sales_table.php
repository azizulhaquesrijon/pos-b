<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAccountTypeIdToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('sales', function (Blueprint $table) {
            $table->renameColumn('account_type_id','account_id');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->renameColumn('account_type_id','account_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('account_type_id','account_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('account_type_id','account_id');
        });

        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->renameColumn('account_type_id','account_id');
        });

        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->renameColumn('account_type_id','account_id');
        });

        Schema::table('cash_flows', function (Blueprint $table) {
            $table->unsignedTinyInteger('account_type_id')->nullable()->after('account_id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            //
        });
    }
}
