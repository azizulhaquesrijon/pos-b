<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->unsignedBigInteger('refer_customer_id')->nullable()->after('customer_id');
            $table->unsignedBigInteger('refer_id')->nullable()->after('refer_customer_id');

            $table->foreign('refer_customer_id')->references('id')->on('customers');
            $table->foreign('refer_id')->references('id')->on('customer_refers');
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
