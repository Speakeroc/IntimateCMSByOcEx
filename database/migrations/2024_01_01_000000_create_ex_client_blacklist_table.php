<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_client_blacklist', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 40);
            $table->longText('text');
            $table->integer('rating');
            $table->integer('user_id');
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_client_blacklist');
    }
};
