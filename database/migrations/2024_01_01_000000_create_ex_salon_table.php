<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_salon', function (Blueprint $table) {
            $table->id();
            $table->text('uniq_uid');

            //Main
            $table->integer('user_id'); //ID Пользователя

            $table->string('title', 64); //Имя
            $table->string('phone', 50)->nullable(); //Телефоны
            $table->string('phone_one', 50)->nullable(); //Телефоны 1
            $table->string('phone_two', 50)->nullable(); //Телефоны 2
            $table->json('messengers')->nullable(); //Мессенджеры
            $table->string('address', 100)->nullable(); //Адрес
            $table->string('desc', 3000)->nullable(); //Описание
            $table->dateTime('up_date')->nullable(); //Дата поднятия

            //Prices
            $table->integer('price_day_in_one')->nullable(); //День 1 час Апартаменты
            $table->integer('price_day_in_two')->nullable(); //День 2 час Апартаменты
            $table->integer('price_day_out_one')->nullable(); //День 1 час Выезд
            $table->integer('price_day_out_two')->nullable(); //День 2 час Выезд
            $table->integer('price_night_in_one')->nullable(); //Ньчь 1 час Апартаменты
            $table->integer('price_night_in_night')->nullable(); //Ньчь ночь Апартаменты
            $table->integer('price_night_out_one')->nullable(); //Ньчь 1 час Выезд
            $table->integer('price_night_out_night')->nullable(); //Ньчь ночь Выезд

            //Location
            $table->integer('city_id')->nullable(); //ID Города
            $table->integer('zone_id')->nullable(); //ID Районов
            $table->integer('metro_id')->nullable(); //ID Городов
            $table->text('latitude')->nullable(); //Широта
            $table->text('longitude')->nullable(); //Долгота

            //Other
            $table->integer('work_time_type')->nullable(); //Тип времени для звонка
            $table->json('work_time')->nullable(); //Время для звонка

            //Statuses
            $table->integer('moderation_id')->nullable(); //Тип модерации
            $table->integer('moderator_id')->nullable(); //ID Модератора
            $table->string('moderation_text', 1000)->nullable(); //Текст модерации
            $table->string('delete_code')->nullable(); //Код для удаления
            $table->integer('publish'); //Статус
            $table->datetime('publish_date')->nullable(); //Дата окончания отображения

            $table->integer('views_salon_uniq')->default('0')->nullable();
            $table->integer('views_salon_all')->default('0')->nullable();
            $table->integer('views_phone_uniq')->default('0')->nullable();
            $table->integer('views_phone_all')->default('0')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_salon');
    }
};
