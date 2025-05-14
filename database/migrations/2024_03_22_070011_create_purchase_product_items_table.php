<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_product_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_type')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('tax_type')->nullable();
            $table->string('purchase_unit')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('order_tax', 8, 2)->nullable();
            $table->decimal('sub_total', 8, 2);
            $table->string('stock')->nullable();
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
        Schema::dropIfExists('purchase_product_items');
    }
}
