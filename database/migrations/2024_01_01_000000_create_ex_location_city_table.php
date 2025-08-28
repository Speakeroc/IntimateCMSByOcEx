<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_location_city', function (Blueprint $table) {
            $table->id();
            $table->string('title','255'); //Название
            $table->text('latitude')->nullable(); //Широта
            $table->text('longitude')->nullable(); //Долгота
            $table->string('city_code')->nullable(); //Код города
            $table->integer('status'); //Статус
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_location_city');
    }
};
