<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('about_us', function (Blueprint $table) {
            $table->id();
            $table->string('section_1_title')->nullable();
            $table->string('section_1_desc')->nullable();
            $table->text('section_1_image')->nullable();
            $table->string('section_2_title')->nullable();
            $table->string('section_2_desc')->nullable();
            $table->text('section_2_image')->nullable();
            $table->json('our_services')->nullable();
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
        Schema::dropIfExists('about_us');
    }
}
