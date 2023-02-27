<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->after('name', function($table){
                $table->string('address')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('image')->nullable();
            });
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {

            $table->dropColumn(['address', 'phone_number', 'image']);

        });
    }
}
