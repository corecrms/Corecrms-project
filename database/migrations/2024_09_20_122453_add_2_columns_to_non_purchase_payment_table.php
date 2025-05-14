<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2ColumnsToNonPurchasePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('non_purchase_payment', function (Blueprint $table) {
            $table->string('payment_method')->nullable();
            $table->foreignId('card_id')->nullable()->constrained('admin_credit_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('non_purchase_payment', function (Blueprint $table) {
            $table->dropColumn('payment_method');
            $table->dropForeign('card_id');
            $table->dropColumn('card_id');
        });
    }
}
