<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_return_items', function (Blueprint $table) {
            $table->id();
            // $table->string('manual_return_id');
            $table->foreignId('manual_return_id')->constrained('manual_returns')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('return_unit')->nullable()->constrained('units')->onDelete('cascade');
            $table->string('return_quantity')->nullable();
            $table->string('price')->nullable();
            $table->string('subtotal')->nullable();
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
        Schema::dropIfExists('manual_return_items');
    }
}
