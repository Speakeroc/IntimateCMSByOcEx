<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GlobalDataServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $site_name = 'Пантера';

        $post_template = [
            ['id' => 'style_1', 'title' => 'Стиль №1'],
            ['id' => 'style_2', 'title' => 'Стиль №2'],
            ['id' => 'style_3', 'title' => 'Стиль №3'],
            ['id' => 'style_4', 'title' => 'Стиль №4'],
        ];

        $salon_template = [
            ['id' => 'big_salon', 'title' => 'Стиль №1'],
        ];

        $order_status_ids = [
            ['id' => 1, 'title' => 'Новая транзакция', 'short_title' => 'Создано', 'color' => '#808080', 'client_color' => '#808080'],
            ['id' => 2, 'title' => 'Ожидание', 'short_title' => 'В ожидании', 'color' => '#FFA500', 'client_color' => '#686868'],
            ['id' => 3, 'title' => 'Оплачено онлайн', 'short_title' => 'Успешно', 'color' => '#008000', 'client_color' => '#004f00'],
            ['id' => 4, 'title' => 'Неудачная оплата', 'short_title' => 'Не успешно', 'color' => '#FF0000', 'client_color' => '#710000']
        ];

        $language_skills = [
            ['id' => 1, 'title' => 'Русский'],
            ['id' => 2, 'title' => 'Украинский'],
            ['id' => 3, 'title' => 'Английский'],
            ['id' => 4, 'title' => 'Французский'],
            ['id' => 5, 'title' => 'Немецкий'],
            ['id' => 6, 'title' => 'Ирландский'],
            ['id' => 7, 'title' => 'Швейцарский'],
            ['id' => 8, 'title' => 'Норвежский'],
            ['id' => 9, 'title' => 'Арабский'],
            ['id' => 10, 'title' => 'Финский'],
            ['id' => 11, 'title' => 'Дацкий'],
            ['id' => 12, 'title' => 'Австрийский'],
            ['id' => 13, 'title' => 'Шведский'],
            ['id' => 14, 'title' => 'Бельгийский'],
            ['id' => 15, 'title' => 'Литовский'],
            ['id' => 16, 'title' => 'Эстонский'],
            ['id' => 17, 'title' => 'Латышский'],
            ['id' => 18, 'title' => 'Итальянский'],
            ['id' => 19, 'title' => 'Испанский'],
            ['id' => 20, 'title' => 'Греческий'],
        ];

        $hair_color = [
            ['id' => 1, 'title' => 'Брюнетка'],
            ['id' => 2, 'title' => 'Блондинка'],
            ['id' => 3, 'title' => 'Шатенка'],
            ['id' => 4, 'title' => 'Рыжая'],
            ['id' => 5, 'title' => 'Русая'],
            ['id' => 6, 'title' => 'Лысая'],
        ];

        $nationality = [
            ['id' => 1, 'title' => 'Русская'],
            ['id' => 2, 'title' => 'Украинка'],
            ['id' => 3, 'title' => 'Белоруска'],
            ['id' => 4, 'title' => 'Узбечка'],
            ['id' => 5, 'title' => 'Казашка'],
            ['id' => 6, 'title' => 'Негритянка'],
            ['id' => 7, 'title' => 'Армянка'],
            ['id' => 8, 'title' => 'Мулатка'],
            ['id' => 9, 'title' => 'Китаянка'],
            ['id' => 10, 'title' => 'Таджичка'],
            ['id' => 11, 'title' => 'Болгарка'],
            ['id' => 12, 'title' => 'Бурятка'],
            ['id' => 13, 'title' => 'Грузинка'],
            ['id' => 14, 'title' => 'Еврейка'],
            ['id' => 15, 'title' => 'Киргизка'],
            ['id' => 16, 'title' => 'Кореянка'],
            ['id' => 17, 'title' => 'Литовка'],
            ['id' => 18, 'title' => 'Осетинка'],
            ['id' => 19, 'title' => 'Татарка'],
            ['id' => 20, 'title' => 'Цыганка'],
            ['id' => 21, 'title' => 'Американка'],
            ['id' => 22, 'title' => 'Мексиканка'],
            ['id' => 23, 'title' => 'Немка'],
            ['id' => 24, 'title' => 'Француженка'],
            ['id' => 25, 'title' => 'Итальянка'],
            ['id' => 26, 'title' => 'Англичанка'],
            ['id' => 27, 'title' => 'Испанка'],
            ['id' => 28, 'title' => 'Японка'],
            ['id' => 29, 'title' => 'Индийка'],
            ['id' => 30, 'title' => 'Турчанка'],
            ['id' => 31, 'title' => 'Филиппинка'],
            ['id' => 32, 'title' => 'Вьетнамка'],
            ['id' => 33, 'title' => 'Бразильянка'],
            ['id' => 34, 'title' => 'Гречанка'],
            ['id' => 35, 'title' => 'Шведка'],
            ['id' => 36, 'title' => 'Португалка'],
            ['id' => 37, 'title' => 'Полька'],
            ['id' => 38, 'title' => 'Финка'],
            ['id' => 39, 'title' => 'Норвежка'],
            ['id' => 40, 'title' => 'Австралийка'],
        ];

        $body_type = [
            ['id' => 1, 'title' => 'Худая'],
            ['id' => 2, 'title' => 'Стройная'],
            ['id' => 3, 'title' => 'Спортивная'],
            ['id' => 4, 'title' => 'Плотная'],
            ['id' => 5, 'title' => 'Толстая']
        ];

        $hairy = [
            ['id' => 1, 'title' => 'Полная депиляция'],
            ['id' => 2, 'title' => 'Аккуратная стрижка'],
            ['id' => 3, 'title' => 'Натуральная']
        ];

        $body_art = [
            ['id' => 1, 'title' => 'Пирсинг'],
            ['id' => 2, 'title' => 'Тату'],
            ['id' => 3, 'title' => 'Силикон в груди'],
            ['id' => 4, 'title' => 'Силикон в ягодицах']
        ];

        $visit_places = [
            ['id' => 1, 'title' => 'В квартиру'],
            ['id' => 2, 'title' => 'За город'],
            ['id' => 3, 'title' => 'В сауну'],
            ['id' => 4, 'title' => 'В гостиницу'],
            ['id' => 5, 'title' => 'В офис'],
            ['id' => 6, 'title' => 'В коттедж']
        ];

        $services_for = [
            ['id' => 1, 'title' => 'Мужчине'],
            ['id' => 2, 'title' => 'Женщине'],
            ['id' => 3, 'title' => 'Паре'],
            ['id' => 4, 'title' => 'Группе']
        ];

        $prices = [
            ['id' => 'one_hour', 'title' => '1 час'],
            ['id' => 'two_hour', 'title' => '2 часа'],
            ['id' => 'night', 'title' => 'Ночь'],
        ];

        $services_sex = [
            'title' => 'Секс',
            'data' => [
                ['id' => 1, 'title' => 'Секс классический'],
                ['id' => 2, 'title' => 'Секс анальный'],
                ['id' => 3, 'title' => 'Секс групповой'],
                ['id' => 4, 'title' => 'Секс лесбийский'],
                ['id' => 5, 'title' => 'Услуги семейной паре'],
                ['id' => 6, 'title' => 'Секс-игрушки']
            ]
        ];

        $services_blow_job = [
            'title' => 'Минет',
            'data' => [
                ['id' => 21, 'title' => 'Минет в презервативе'],
                ['id' => 22, 'title' => 'Минет без резинки'],
                ['id' => 23, 'title' => 'Минет глубокий'],
                ['id' => 24, 'title' => 'Минет в машине'],
                ['id' => 25, 'title' => 'Кунилингус']
            ]
        ];

        $services_ending = [
            'title' => 'Окончание',
            'data' => [
                ['id' => 41, 'title' => 'На грудь'],
                ['id' => 42, 'title' => 'На лицо'],
                ['id' => 43, 'title' => 'В рот']
            ]
        ];

        $services_striptease = [
            'title' => 'Стриптиз',
            'data' => [
                ['id' => 61, 'title' => 'Стриптиз профи'],
                ['id' => 62, 'title' => 'Стриптиз не профи'],
                ['id' => 63, 'title' => 'Лесби откровенное'],
                ['id' => 64, 'title' => 'Лесби-шоу легкое']
            ]
        ];

        $services_massage = [
            'title' => 'Массаж',
            'data' => [
                ['id' => 81, 'title' => 'Массаж классический'],
                ['id' => 82, 'title' => 'Массаж профессиональный'],
                ['id' => 83, 'title' => 'Массаж расслабляющий'],
                ['id' => 84, 'title' => 'Массаж тайский'],
                ['id' => 85, 'title' => 'Массаж урологический'],
                ['id' => 86, 'title' => 'Массаж точечный'],
                ['id' => 87, 'title' => 'Массаж эротический'],
                ['id' => 88, 'title' => 'Массаж ветка сакуры']
            ]
        ];

        $services_extreme = [
            'title' => 'Экстрим',
            'data' => [
                ['id' => 101, 'title' => 'Страпон'],
                ['id' => 102, 'title' => 'Анилингус делаю'],
                ['id' => 103, 'title' => 'Золотой дождь выдача'],
                ['id' => 104, 'title' => 'Золотой дождь прием'],
                ['id' => 105, 'title' => 'Копро выдача'],
                ['id' => 106, 'title' => 'Фистинг анальный'],
                ['id' => 107, 'title' => 'Фистинг классический']
            ]
        ];

        $services_bdsm = [
            'title' => 'БДСМ',
            'data' => [
                ['id' => 121, 'title' => 'Госпожа'],
                ['id' => 122, 'title' => 'Игры'],
                ['id' => 123, 'title' => 'Легкая доминация'],
                ['id' => 124, 'title' => 'Порка'],
                ['id' => 125, 'title' => 'Рабыня'],
                ['id' => 126, 'title' => 'Фетиш'],
                ['id' => 127, 'title' => 'Трамплинг'],
                ['id' => 128, 'title' => 'Бондаж']
            ]
        ];

        $services_miscellaneous = [
            'title' => 'Разное',
            'data' => [
                ['id' => 141, 'title' => 'GFE'],
                ['id' => 142, 'title' => 'Сопровождение'],
                ['id' => 143, 'title' => 'Готова к поездкам в другой город'],
                ['id' => 144, 'title' => 'Есть загранпаспорт'],
                ['id' => 145, 'title' => 'Ролевые игры'],
                ['id' => 146, 'title' => 'Фото/видео съемка'],
                ['id' => 147, 'title' => 'Эскорт'],
                ['id' => 148, 'title' => 'Виртуальный секс']
            ]
        ];

        $all_services = [
            1 => $services_sex,
            2 => $services_blow_job,
            3 => $services_ending,
            4 => $services_striptease,
            5 => $services_massage,
            6 => $services_extreme,
            7 => $services_bdsm,
            8 => $services_miscellaneous
        ];

        $social_links = [
            ['value' => 'youtube', 'title' => 'YouTube'],
            ['value' => 'facebook', 'title' => 'Facebook'],
            ['value' => 'instagram', 'title' => 'Instagram'],
            ['value' => 'pinterest', 'title' => 'Pinterest'],
            ['value' => 'linkedin', 'title' => 'LinkedIn'],
            ['value' => 'snapchat', 'title' => 'Snapchat'],
            ['value' => 'twitter', 'title' => 'Twitter'],
            ['value' => 'telegram', 'title' => 'Telegram'],
            ['value' => 'whatsapp', 'title' => 'WhatsApp'],
            ['value' => 'tiktok', 'title' => 'TikTok'],
            ['value' => 'wechat', 'title' => 'WeChat'],
        ];

        $image_settings = [
            'posts' => [
                'style_1' => 500,
                'style_2' => 275,
                'style_3' => 450,
                'style_4' => 440,
                'post_main' => 440,
                'post_photo_small' => 200,
                'post_photo_big' => 500,
                'post_selfie_small' => 200,
                'post_selfie_big' => 500,
                'in_account' => 450,
            ],
            'salon' => [
                'main' => 500,
            ],
        ];

        $moderation_status = [
            ['id' => 0, 'title' => 'На модерации'],
            ['id' => 1, 'title' => 'Модерация пройдена'],
            ['id' => 2, 'title' => 'Модерация не пройдена'],
            ['id' => 3, 'title' => 'Отклонено'],
        ];

        $this->app->instance('site_name', $site_name);
        $this->app->instance('post_hair_color', $hair_color);
        $this->app->instance('post_body_type', $body_type);
        $this->app->instance('post_hairy', $hairy);
        $this->app->instance('post_body_art', $body_art);
        $this->app->instance('post_visit_places', $visit_places);
        $this->app->instance('post_services_for', $services_for);
        $this->app->instance('post_nationality', $nationality);
        $this->app->instance('post_language_skills', $language_skills);
        $this->app->instance('order_status_ids', $order_status_ids);
        $this->app->instance('post_prices', $prices);
        $this->app->instance('post_services', $all_services);
        $this->app->instance('post_social_links', $social_links);
        $this->app->instance('post_template', $post_template);
        $this->app->instance('salon_template', $salon_template);
        $this->app->instance('image_settings', $image_settings);
        $this->app->instance('moderation_status', $moderation_status);
    }

    public function boot(): void
    {
        //
    }
}
