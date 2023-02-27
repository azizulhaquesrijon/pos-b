<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferedByToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('refer_by_user_id')->nullable()->after('is_default');
            $table->unsignedBigInteger('refer_by_customer_id')->nullable()->after('refer_by_user_id');

            $table->foreign('refer_by_user_id')->references('id')->on('users');
            $table->foreign('refer_by_customer_id')->references('id')->on('customers');
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
