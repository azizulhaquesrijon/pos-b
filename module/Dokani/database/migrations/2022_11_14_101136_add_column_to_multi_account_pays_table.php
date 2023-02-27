<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToMultiAccountPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('multi_account_pays', function (Blueprint $table) {
            $table->string('check_no')->nullable()->after('amount');
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
        Schema::table('multi_account_pays', function (Blueprint $table) {
            //
        });
    }
}
