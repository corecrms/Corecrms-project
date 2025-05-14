<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('reference');
            $table->foreignId('from_warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->foreignId('to_warehouse_id')->nullable()->constrained('warehouses')->onDelete('cascade');
            $table->string('grand_total')->nullable();
            $table->string('discount')->nullable();
            $table->string('order_tax')->nullable();
            $table->string('shipping')->nullable();
            $table->string('items')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();

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
        Schema::dropIfExists('transfers');
    }
}
