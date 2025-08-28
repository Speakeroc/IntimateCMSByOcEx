<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_news_rate_history', function (Blueprint $table) {
            $table->id();
            $table->integer('news_id');
            $table->string('sign','255');
            $table->string('type','255');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_news_rate_history');
    }
};
