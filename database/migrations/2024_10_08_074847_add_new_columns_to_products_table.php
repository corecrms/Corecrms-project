<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_weight_unit')->nullable();
            $table->string('product_weight')->nullable();
            $table->string('product_dimension_unit')->nullable();
            $table->string('product_length')->nullable();
            $table->string('product_width')->nullable();
            $table->string('product_height')->nullable();
            $table->string('product_imei_no')->nullable();
            $table->string('ailse')->nullable();
            $table->string('rack')->nullable();
            $table->string('shelf')->nullable();
            $table->string('bin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_weight_unit');
            $table->dropColumn('product_weight');
            $table->dropColumn('product_dimension_unit');
            $table->dropColumn('product_length');
            $table->dropColumn('product_width');
            $table->dropColumn('product_height');
            $table->dropColumn('product_imei_no');
            $table->dropColumn('ailse');
            $table->dropColumn('rack');
            $table->dropColumn('shelf');
            $table->dropColumn('bin');
        });
    }
}
