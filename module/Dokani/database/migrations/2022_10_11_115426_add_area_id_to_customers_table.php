<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaIdToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('cus_area_id')->nullable()->after('updated_by');
            $table->unsignedBigInteger('cus_category_id')->nullable()->after('cus_area_id');

            $table->foreign('cus_area_id')->references('id')->on('cus_areas');
            $table->foreign('cus_category_id')->references('id')->on('cus_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
