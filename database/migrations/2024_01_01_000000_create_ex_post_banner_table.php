<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_post_banner', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('post_id')->nullable();
            $table->string('link', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('activation');
            $table->dateTime('activation_date');
            $table->dateTime('up_date');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_post_banner');
    }
};
