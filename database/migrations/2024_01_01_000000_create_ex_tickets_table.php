<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('subject', 255);
            $table->integer('status_id')->default(1);
            $table->integer('view_admin')->default(0);
            $table->integer('view_user')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_tickets');
    }
};
