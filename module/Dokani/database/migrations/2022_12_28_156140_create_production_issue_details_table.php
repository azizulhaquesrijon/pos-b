<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_issue_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_issue_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 16,6)->default(0);
            $table->decimal('unit_price', 16,6)->default(0);
            $table->decimal('sub_total', 16,6)->virtualAs('quantity*unit_price');
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('production_issue_id')->references('id')->on('production_issue');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('production_issue_details');
    }
}
