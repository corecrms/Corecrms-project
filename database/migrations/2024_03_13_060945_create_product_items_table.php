<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_type')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->string('stock')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('tax_type')->nullable();
            $table->string('sale_unit')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('order_tax', 8, 2)->nullable();
            $table->decimal('sub_total', 8, 2);
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
        Schema::dropIfExists('product_items');
    }
}
