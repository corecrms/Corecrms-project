<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('linkedin')->nullable();
            $table->string('fb')->nullable();
            $table->string('twitch')->nullable();
            $table->string('twitter')->nullable();
            $table->string('currency')->nullable();
            $table->string('email')->nullable();
            $table->text('logo')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('developed_by')->nullable();
            $table->string('footer')->nullable();
            $table->string('default_lang')->nullable();
            $table->unsignedBigInteger('default_customer')->nullable();
            $table->foreign('default_customer')->references('id')->on('customers')->onDelete('cascade');
            $table->unsignedBigInteger('default_warehouse')->nullable();
            $table->foreign('default_warehouse')->references('id')->on('warehouses')->onDelete('cascade');
            $table->string('sms_gateway')->nullable();
            $table->string('time_zone')->nullable();
            $table->text('address')->nullable();
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('smtp_address')->nullable();
            $table->string('smtp_from_name')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
