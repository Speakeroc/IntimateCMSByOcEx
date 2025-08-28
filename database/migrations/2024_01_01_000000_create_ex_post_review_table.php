<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_post_review', function (Blueprint $table) {
            $table->id();
            $table->longText('text');
            $table->integer('rating');
            $table->integer('user_id')->default(0);
            $table->integer('post_id');
            $table->integer('views')->default(0);

            $table->integer('moderation_id')->nullable(); //Тип модерации
            $table->integer('moderator_id')->nullable(); //ID Модератора
            $table->integer('publish'); //Статус
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_post_review');
    }
};
