<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->string('check_no')->nullable()->after('account_id');
            $table->string('check_date')->nullable()->after('check_no');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('check_no')->nullable()->after('account_id');
            $table->string('check_date')->nullable()->after('check_no');
        });


        Schema::table('voucher_payments', function (Blueprint $table) {

            $table->string('check_no')->nullable()->after('account_id');
            $table->string('check_date')->nullable()->after('check_no');
        });

        Schema::table('fund_transfers', function (Blueprint $table) {

            $table->string('from_check_no')->nullable()->after('from_account_id');
            $table->string('from_check_date')->nullable()->after('from_check_no');

            $table->string('to_check_no')->nullable()->after('to_account_id');
            $table->string('to_check_date')->nullable()->after('to_check_no');
        });

        Schema::table('investors', function (Blueprint $table) {

            $table->string('check_no')->nullable()->after('account_id');
            $table->string('check_date')->nullable()->after('check_no');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {
            //
        });
    }
}
