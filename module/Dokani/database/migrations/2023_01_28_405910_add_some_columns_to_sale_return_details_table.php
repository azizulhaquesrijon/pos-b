<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsToSaleReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_return_details', function (Blueprint $table) {
            $table->unsignedBigInteger('sale_details_id')->after('sale_return_id')->nullable();
            $table->string('lot')->nullable()->after('sale_details_id');

            $table->foreign('sale_details_id')->references('id')->on('sale_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_return_details', function (Blueprint $table) {
            $table->dropForeign(['sale_details_id']);
            $table->dropColumn('sale_details_id');
            $table->dropColumn('lot');
        });
    }
}
