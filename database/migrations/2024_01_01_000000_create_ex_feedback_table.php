<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_feedback', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('theme','255');
            $table->text('name');
            $table->text('email');
            $table->text('message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_feedback');
    }
};
