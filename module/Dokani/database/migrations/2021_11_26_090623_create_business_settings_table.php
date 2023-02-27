<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('cover_image')->nullable();
            $table->unsignedBigInteger('shop_type_id')->nullable();
            $table->string('shop_name')->nullable();
            $table->string('shop_name_bn')->nullable();
            $table->string('shop_address')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_mobile')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('nid_front_image')->nullable();
            $table->string('nid_back_image')->nullable();
            $table->string('trade_license_image')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_settings');
    }
}
