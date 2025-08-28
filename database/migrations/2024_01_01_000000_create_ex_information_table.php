<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_information', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('title','255');
            $table->text('desc');
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('seo_url')->nullable();
            $table->integer('views')->default(0);
            $table->integer('status');
            $table->integer('in_menu')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_information');
    }
};
