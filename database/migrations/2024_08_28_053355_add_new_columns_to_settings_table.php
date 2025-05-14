<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('fedex_api_key')->nullable();
            $table->string('fedex_secret_key')->nullable();
            $table->string('fedex_account_number')->nullable();
            $table->string('fedex_meter_number')->nullable();
            $table->string('fedex_api_url')->nullable()->default('https://apis-sandbox.fedex.com');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('fedex_api_key');
            $table->dropColumn('fedex_secret_key');
            $table->dropColumn('fedex_account_number');
            $table->dropColumn('fedex_meter_number');
            $table->dropColumn('fedex_api_url');

        });
    }
}
