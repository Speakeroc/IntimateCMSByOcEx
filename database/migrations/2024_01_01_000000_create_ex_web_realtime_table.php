<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_web_realtime', function (Blueprint $table) {
            $table->id();
            $table->string('unique_code', 32);
            $table->string('ip_address', 50);
            $table->string('url', 256);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_web_realtime');
    }
};
