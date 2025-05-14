<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->date("bill_date")->nullable();
            $table->date("due_date")->nullable();
            $table->foreignId("vendor_id")->constrained('vendors')->onDelete("cascade");
            $table->foreignId("warehouse_id")->constrained('warehouses')->onDelete("cascade");
            $table->string('details')->nullable();
            $table->string('discount')->nullable();
            $table->string('tax')->nullable();
            $table->string('grand_total')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
