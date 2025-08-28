<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ex_post', function (Blueprint $table) {
            $table->id();
            $table->text('uniq_uid');

            //Main
            $table->integer('user_id'); //ID Пользователя
            $table->integer('salon_id')->default('0')->nullable(); //Салон
            $table->json('category_ids')->nullable(); //ID Категорий

            //Sections
            $table->integer('s_individuals')->default('0')->nullable(); //Раздел Индивидуалки
            $table->integer('s_premium')->default('0')->nullable(); //Премиум
            $table->integer('s_health')->default('0')->nullable(); //Справка о здоровье
            $table->integer('s_elite')->default('0')->nullable(); //Раздел Элитные
            $table->integer('s_bdsm')->default('0')->nullable(); //Раздел БДСМ
            $table->integer('s_masseuse')->default('0')->nullable(); //Раздел Масажистки
            $table->integer('section_8')->default('0')->nullable(); //Раздел на запас
            $table->integer('section_9')->default('0')->nullable(); //Раздел на запас
            $table->integer('section_10')->default('0')->nullable(); //Раздел на запас

            $table->string('name', 64); //Имя
            $table->integer('age'); //Возраст
            $table->string('phone'); //Телефон
            $table->json('messengers')->nullable(); //Мессенджеры
            $table->json('tags')->nullable(); //Теги
            $table->string('description', 3000); //Описание
            $table->integer('verify')->default('0'); //Статус верификации
            $table->integer('diamond')->default('0'); //Diamond статус
            $table->datetime('diamond_date')->nullable(); //Дата окончания Diamond
            $table->integer('vip')->default('0'); //VIP статус
            $table->datetime('vip_date')->nullable(); //Дата окончания VIP
            $table->integer('color')->default('0'); //Color статус
            $table->datetime('color_date')->nullable(); //Дата окончания Color
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
            $table->integer('express')->nullable(); //Есть экспрес
            $table->integer('express_price')->nullable(); //Цена для экспреса

            //Location
            $table->integer('city_id')->nullable(); //ID Города
            $table->integer('zone_id')->nullable(); //ID Районов
            $table->integer('metro_id')->nullable(); //ID Городов
            $table->text('latitude')->nullable(); //Широта
            $table->text('longitude')->nullable(); //Долгота

            //Attributes
            $table->json('language_skills')->nullable(); //Знание языков
            $table->json('client_age')->nullable(); //Минимальный-Максимальный возраст клиента
            $table->integer('hair_color')->nullable(); //Цвет волос
            $table->integer('nationality')->nullable(); //Национальность
            $table->integer('body_type')->nullable(); //Телосложение
            $table->integer('hairy')->nullable(); //Интимная стрижка
            $table->integer('cloth')->nullable(); //Размер одежды
            $table->integer('shoes')->nullable(); //Размер обуви
            $table->integer('height')->nullable(); //Рост
            $table->integer('weight')->nullable(); //Вес
            $table->integer('breast')->nullable(); //Размер груди

            //Other
            $table->integer('call_time_type')->nullable(); //Тип времени для звонка
            $table->json('call_time')->nullable(); //Время для звонка
            $table->json('body_art')->nullable(); //Боди-арт
            $table->json('visit_places')->nullable(); //Выезд
            $table->json('services')->nullable(); //Услуги
            $table->json('services_for')->nullable(); //Услуги для

            //Statuses
            $table->integer('moderation_id')->nullable(); //Тип модерации
            $table->integer('moderator_id')->nullable(); //ID Модератора
            $table->string('moderation_text', 1000)->nullable(); //Текст модерации
            $table->string('delete_code')->nullable(); //Код для удаления
            $table->integer('user_publish')->default('0'); //Статус
            $table->integer('publish'); //Статус
            $table->datetime('publish_date')->nullable(); //Дата окончания отображения

            $table->integer('views_post_uniq')->default('0')->nullable();
            $table->integer('views_post_all')->default('0')->nullable();
            $table->integer('views_phone_uniq')->default('0')->nullable();
            $table->integer('views_phone_all')->default('0')->nullable();
            $table->integer('transition_telegram_uniq')->default('0')->nullable();
            $table->integer('transition_telegram_all')->default('0')->nullable();
            $table->integer('transition_whatsapp_uniq')->default('0')->nullable();
            $table->integer('transition_whatsapp_all')->default('0')->nullable();
            $table->integer('transition_instagram_uniq')->default('0')->nullable();
            $table->integer('transition_instagram_all')->default('0')->nullable();
            $table->integer('transition_polee_uniq')->default('0')->nullable();
            $table->integer('transition_polee_all')->default('0')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ex_post');
    }
};
