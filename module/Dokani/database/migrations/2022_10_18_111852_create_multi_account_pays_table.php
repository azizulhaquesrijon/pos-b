<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiAccountPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multi_account_pays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokan_id');
            $table->morphs('source');
            $table->unsignedTinyInteger('account_id');
            $table->decimal('amount',16,4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('multi_account_pays');
    }
}
