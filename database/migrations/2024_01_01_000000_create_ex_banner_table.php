<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_banner', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title','255');
            $table->string('banner', 255)->nullable();
            $table->text('link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_banner');
    }
};
