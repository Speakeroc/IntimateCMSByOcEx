<?php

namespace Database\Seeders;

use App\Models\location\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run() {
        City::create([ 'title' => 'Астрахань', 'latitude' => '46.3497', 'longitude' => '48.0408', 'city_code' => 'astrakhan', 'status' => 1]);
        City::create([ 'title' => 'Барнаул', 'latitude' => '53.3478', 'longitude' => '83.7769', 'city_code' => 'barnaul', 'status' => 1]);
        City::create([ 'title' => 'Белгород', 'latitude' => '50.5977', 'longitude' => '36.5850', 'city_code' => 'belgorod', 'status' => 1]);
        City::create([ 'title' => 'Брянск', 'latitude' => '53.2521', 'longitude' => '34.3717', 'city_code' => 'bryansk', 'status' => 1]);
        City::create([ 'title' => 'Волгоград', 'latitude' => '48.7080', 'longitude' => '44.5133', 'city_code' => 'volgograd', 'status' => 1]);
        City::create([ 'title' => 'Воронеж', 'latitude' => '51.6615', 'longitude' => '39.2003', 'city_code' => 'voronezh', 'status' => 1]);
        City::create([ 'title' => 'Екатеринбург', 'latitude' => '56.8389', 'longitude' => '60.6057', 'city_code' => 'yekaterinburg', 'status' => 1]);
        City::create([ 'title' => 'Ижевск', 'latitude' => '56.8526', 'longitude' => '53.2045', 'city_code' => 'izhevsk', 'status' => 1]);
        City::create([ 'title' => 'Иркутск', 'latitude' => '52.2978', 'longitude' => '104.2964', 'city_code' => 'irkutsk', 'status' => 1]);
        City::create([ 'title' => 'Казань', 'latitude' => '55.8304', 'longitude' => '49.0661', 'city_code' => 'kazan', 'status' => 1]);
        City::create([ 'title' => 'Кемерово', 'latitude' => '55.3547', 'longitude' => '86.0879', 'city_code' => 'kemerovo', 'status' => 1]);
        City::create([ 'title' => 'Киров', 'latitude' => '58.6035', 'longitude' => '49.6679', 'city_code' => 'kirov', 'status' => 1]);
        City::create([ 'title' => 'Краснодар', 'latitude' => '45.0355', 'longitude' => '38.9753', 'city_code' => 'krasnodar', 'status' => 1]);
        City::create([ 'title' => 'Красноярск', 'latitude' => '56.0153', 'longitude' => '92.8932', 'city_code' => 'krasnoyarsk', 'status' => 1]);
        City::create([ 'title' => 'Курган', 'latitude' => '55.4507', 'longitude' => '65.3411', 'city_code' => 'kurgan', 'status' => 1]);
        City::create([ 'title' => 'Курск', 'latitude' => '51.7304', 'longitude' => '36.1926', 'city_code' => 'kursk', 'status' => 1]);
        City::create([ 'title' => 'Липецк', 'latitude' => '52.6031', 'longitude' => '39.5708', 'city_code' => 'lipetsk', 'status' => 1]);
        City::create([ 'title' => 'Магнитогорск', 'latitude' => '53.4072', 'longitude' => '58.9791', 'city_code' => 'magnitogorsk', 'status' => 1]);
        City::create([ 'title' => 'Москва', 'latitude' => '55.7512', 'longitude' => '37.6184', 'city_code' => 'moskva', 'status' => 1]);
        City::create([ 'title' => 'Мурманск', 'latitude' => '68.9585', 'longitude' => '33.0827', 'city_code' => 'murmansk', 'status' => 1]);
        City::create([ 'title' => 'Нижний Новгород', 'latitude' => '56.3269', 'longitude' => '44.0059', 'city_code' => 'nizhniy-novgorod', 'status' => 1]);
        City::create([ 'title' => 'Новосибирск', 'latitude' => '55.0084', 'longitude' => '82.9357', 'city_code' => 'novosibirsk', 'status' => 1]);
        City::create([ 'title' => 'Омск', 'latitude' => '54.9924', 'longitude' => '73.3686', 'city_code' => 'omsk', 'status' => 1]);
        City::create([ 'title' => 'Оренбург', 'latitude' => '51.7682', 'longitude' => '55.0968', 'city_code' => 'orenburg', 'status' => 1]);
        City::create([ 'title' => 'Пенза', 'latitude' => '53.1959', 'longitude' => '45.0183', 'city_code' => 'penza', 'status' => 1]);
        City::create([ 'title' => 'Пермь', 'latitude' => '58.0105', 'longitude' => '56.2294', 'city_code' => 'perm', 'status' => 1]);
        City::create([ 'title' => 'Ростов-на-Дону', 'latitude' => '47.2357', 'longitude' => '39.7015', 'city_code' => 'rostov-na-donu', 'status' => 1]);
        City::create([ 'title' => 'Рязань', 'latitude' => '54.6269', 'longitude' => '39.6916', 'city_code' => 'ryazan', 'status' => 1]);
        City::create([ 'title' => 'Самара', 'latitude' => '53.1959', 'longitude' => '50.1008', 'city_code' => 'samara', 'status' => 1]);
        City::create([ 'title' => 'Санкт-Петербург', 'latitude' => '59.9311', 'longitude' => '30.3609', 'city_code' => 'sankt-peterburg', 'status' => 1]);
        City::create([ 'title' => 'Саратов', 'latitude' => '51.5331', 'longitude' => '46.0342', 'city_code' => 'saratov', 'status' => 1]);
        City::create([ 'title' => 'Смоленск', 'latitude' => '54.7903', 'longitude' => '32.0504', 'city_code' => 'smolensk', 'status' => 1]);
        City::create([ 'title' => 'Сочи', 'latitude' => '43.6028', 'longitude' => '39.7342', 'city_code' => 'sochi', 'status' => 1]);
        City::create([ 'title' => 'Ставрополь', 'latitude' => '45.0448', 'longitude' => '41.9694', 'city_code' => 'stavropol', 'status' => 1]);
        City::create([ 'title' => 'Тольятти', 'latitude' => '53.5200', 'longitude' => '49.3461', 'city_code' => 'tolyatti', 'status' => 1]);
        City::create([ 'title' => 'Томск', 'latitude' => '56.4977', 'longitude' => '84.9744', 'city_code' => 'tomsk', 'status' => 1]);
        City::create([ 'title' => 'Тула', 'latitude' => '54.1931', 'longitude' => '37.6171', 'city_code' => 'tula', 'status' => 1]);
        City::create([ 'title' => 'Тюмень', 'latitude' => '57.1530', 'longitude' => '65.5343', 'city_code' => 'tyumen', 'status' => 1]);
        City::create([ 'title' => 'Уфа', 'latitude' => '54.7388', 'longitude' => '55.9721', 'city_code' => 'ufa', 'status' => 1]);
        City::create([ 'title' => 'Челябинск', 'latitude' => '55.1644', 'longitude' => '61.4368', 'city_code' => 'chelyabinsk', 'status' => 1]);
        City::create([ 'title' => 'Ярославль', 'latitude' => '57.6261', 'longitude' => '39.8845', 'city_code' => 'yaroslavl', 'status' => 1]);

    }
}


