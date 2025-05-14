<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleShippingAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email');
            $table->string('contact_no');
            $table->text('address');
            $table->text('appartment')->nullable();
            $table->string('city');
            $table->string('country');
            $table->text('state')->nullable();
            $table->string('zip_code');
            $table->string('notes')->nullable();
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('sale_shipping_addresses');
    }
}
