<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_posts_category', function (Blueprint $table) {
            $table->id();
            $table->string('image', 64)->nullable();
            $table->string('title','255');
            $table->longText('description')->nullable();
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->text('slug')->nullable();
            $table->integer('only_verify');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_posts_category');
    }
};
