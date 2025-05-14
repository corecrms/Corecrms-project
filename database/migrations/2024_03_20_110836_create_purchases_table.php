<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('warehouse_id')->nullable();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('tier_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('tier_id')->references('id')->on('tiers')->onDelete('cascade');
            $table->integer('ntn')->nullable();
            $table->decimal('order_tax', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->decimal('shipping', 8, 2)->nullable();
            $table->string('status');
            $table->string('payment_status');
            $table->string('payment_method')->nullable();
            $table->string('amount_recieved')->nullable();
            $table->string('amount_pay')->nullable();
            $table->string('change_return')->nullable();
            $table->string('bank_account')->nullable();
            $table->decimal('grand_total', 8, 2)->default(0)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by');
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
        Schema::dropIfExists('purchases');
    }
}
