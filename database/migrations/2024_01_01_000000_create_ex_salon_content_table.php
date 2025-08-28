<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_salon_content', function (Blueprint $table) {
            $table->id();
            $table->integer('salon_id');
            $table->integer('user_id');
            $table->string('file', 255);
            $table->string('type', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_salon_content');
    }
};
