<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_analytics_users_data', function (Blueprint $table) {
            $table->id();
            $table->text('uniq_uid');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_analytics_users_data');
    }
};
