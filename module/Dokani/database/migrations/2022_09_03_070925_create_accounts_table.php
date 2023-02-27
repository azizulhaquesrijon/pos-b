<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokan_id');

            $table->unsignedTinyInteger('account_type_id');
            $table->decimal('opening_balance',16,2)->default(0);
            $table->decimal('balance',16,2);
            $table->unsignedTinyInteger('status')->default(0);

            $table->timestamps();

            $table->foreign('dokan_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
