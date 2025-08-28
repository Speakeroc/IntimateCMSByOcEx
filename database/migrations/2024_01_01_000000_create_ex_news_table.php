<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_news', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title','255');
            $table->text('desc');
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('image', 255)->nullable();
            $table->text('seo_url')->nullable();
            $table->integer('views')->default(0);
            $table->integer('pinned')->default(0)->nullable();
            $table->integer('like')->default(0)->nullable();
            $table->integer('dislike')->default(0)->nullable();
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_news');
    }
};
