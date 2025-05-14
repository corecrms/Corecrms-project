<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFourColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('pos_note')->nullable();
            $table->integer('show_phone')->nullable()->default(0);
            $table->integer('show_address')->nullable()->default(0);
            $table->integer('show_email')->nullable()->default(0);
            $table->integer('show_customer')->nullable()->default(0);
            $table->integer('show_warehouse')->nullable()->default(0);
            $table->integer('show_tax_discount')->nullable()->default(0);
            $table->integer('show_barcode')->nullable()->default(0);
            $table->integer('show_note_to_customer')->nullable()->default(0);
            $table->integer('show_invoice')->nullable()->default(0);
            $table->string('stripe_key')->nullable();
            $table->string('stripe_secret')->nullable();
            $table->integer('delete_stripe_key')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
}
