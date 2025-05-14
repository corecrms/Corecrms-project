<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('APP_NAME')->nullable();
            // smtp
            $table->string('MAIL_HOST')->nullable();
            $table->string('MAIL_PORT')->nullable();
            $table->string('MAIL_USERNAME')->nullable();
            $table->string('MAIL_PASSWORD')->nullable();
            $table->string('MAIL_ENCRYPTION')->nullable();
            $table->string('MAIL_FROM_ADDRESS')->nullable();
            $table->string('MAIL_FROM_NAME')->nullable();
            // facebook credentials
            $table->string('FACEBOOK_CLIENT_ID')->nullable();
            $table->string('FACEBOOK_CLIENT_SECRET')->nullable();
            $table->string('FACEBOOK_REDIRECT')->nullable();
            // google credentials
            $table->string('GOOGLE_CLIENT_ID')->nullable();
            $table->string('GOOGLE_CLIENT_SECRET')->nullable();
            $table->string('GOOGLE_REDIRECT')->nullable();

            // stripe credentials
            $table->string('STRIPE_KEY')->nullable();
            $table->string('STRIPE_SECRET')->nullable();

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
        Schema::dropIfExists('app_settings');
    }
}
