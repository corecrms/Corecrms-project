<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSalesPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add amount_due column to sales table and purchases table
        Schema::table('sales', function (Blueprint $table) {
            $table->string('amount_due')->nullable()->after('notes');
        });
        // also for purchases table
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('amount_due')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop amount_due column from sales table and purchases table
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('amount_due');
        });
        // also for purchases table
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('amount_due');
        });
    }
}
