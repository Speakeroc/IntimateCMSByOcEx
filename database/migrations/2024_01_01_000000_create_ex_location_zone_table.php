<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_location_zone', function (Blueprint $table) {
            $table->id();
            $table->integer('city_id');
            $table->string('title','255');
            $table->integer('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_location_zone');
    }
};
