<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandingPageHeadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landing_page_headings', function (Blueprint $table) {
            $table->id();
            $table->string('top_selling_product')->nullable();
            $table->string('our_recomandation')->nullable();
            $table->string('free_shipping_heading')->nullable();
            $table->string('free_shipping_desc')->nullable();
            $table->string('money_returns_heading')->nullable();
            $table->string('money_returns_desc')->nullable();
            $table->string('secure_payment_heading')->nullable();
            $table->string('secure_payment_desc')->nullable();
            $table->string('support_heading')->nullable();
            $table->string('support_desc')->nullable();
            $table->string('feature_category')->nullable();
            $table->string('shop_by_brands')->nullable();

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
        Schema::dropIfExists('landing_page_headings');
    }
}
