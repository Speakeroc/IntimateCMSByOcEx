<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('balance')->default(0);
            $table->string('login')->index();
            $table->string('name');
            $table->string('password');
            $table->string('type')->nullable();
            $table->integer('user_group_id')->index();
            $table->integer('allow_post_help')->default(2)->nullable();
            $table->string('email');
            $table->integer('email_activate')->default(0)->nullable();
            $table->text('email_activate_code')->nullable();
            $table->dateTime('email_activate_time')->nullable();
            $table->string('new_email')->default('');
            $table->string('remember_token')->default('');
            $table->string('forgot_token')->default('');
            $table->dateTime('forgot_time')->default('2000-01-01 00:00');
            $table->integer('ban')->default(0)->nullable();
            $table->timestamp('last_seen')->nullable();
            $table->text('referer')->nullable();
            $table->string('selected_language', 255)->default('ru');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_users');
    }
};
