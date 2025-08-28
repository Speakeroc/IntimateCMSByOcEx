<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\UsersGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run() {
        UsersGroup::create(['name' => 'Главный Администратор', 'color' => '#FF0000', 'permission' => '{"access":["view\/admin_panel","view\/dashboard","view\/feedback","view\/analytics","view\/ticket_system","view\/post_moderation","view\/review_moderation","view\/salon_moderation","view\/post","view\/salon","view\/post_banner","view\/tags","view\/blacklist","view\/review","view\/post_content_main","view\/post_content_photo","view\/post_content_selfie","view\/post_content_verify","view\/post_content_video","view\/location_city","view\/location_zone","view\/location_metro","view\/information_news","view\/information_information","view\/information_banner","view\/payment_code","view\/payment_aaio","view\/payment_transaction","view\/users","view\/user_group","view\/app_settings"],"modify":["edit\/feedback_edit","edit\/feedback_delete","edit\/ticket_system_write","edit\/ticket_system_delete","edit\/post_moderation","edit\/review_moderation","edit\/salon_moderation","edit\/post_add","edit\/post_edit","edit\/post_delete","edit\/salon_add","edit\/salon_edit","edit\/salon_delete","edit\/post_banner_add","edit\/post_banner_edit","edit\/post_banner_delete","edit\/tags_add","edit\/tags_edit","edit\/tags_delete","edit\/blacklist_add","edit\/blacklist_edit","edit\/blacklist_delete","edit\/review_add","edit\/review_edit","edit\/review_delete","edit\/post_content_main_delete","edit\/post_content_photo_delete","edit\/post_content_selfie_delete","edit\/post_content_verify_delete","edit\/post_content_video_delete","edit\/location_city_add","edit\/location_city_edit","edit\/location_city_delete","edit\/location_zone_add","edit\/location_zone_edit","edit\/location_zone_delete","edit\/location_metro_add","edit\/location_metro_edit","edit\/location_metro_delete","edit\/information_news_add","edit\/information_news_edit","edit\/information_news_delete","edit\/information_information_add","edit\/information_information_edit","edit\/information_information_delete","edit\/information_banner_add","edit\/information_banner_edit","edit\/information_banner_delete","edit\/payment_code_add","edit\/payment_code_edit","edit\/payment_code_delete","edit\/payment_aaio_save","edit\/users_add","edit\/users_edit","edit\/users_delete","edit\/user_group_add","edit\/user_group_edit","edit\/user_group_delete","edit\/app_settings_save"]}']);
        UsersGroup::create(['name' => 'Администраторы', 'color' => '#FFA500', 'permission' => '{"access":["view\/admin_panel","view\/dashboard","view\/post_moderation","view\/salon_moderation","view\/post","view\/salon","view\/post_banner","view\/tags","view\/blacklist","view\/post_content_main","view\/post_content_photo","view\/post_content_selfie","view\/post_content_verify","view\/post_content_video","view\/location_city","view\/location_zone","view\/location_metro","view\/information_news","view\/information_banner","view\/payment_code","view\/payment_transaction","view\/users"],"modify":["edit\/post_moderation","edit\/salon_moderation","edit\/post_add","edit\/post_edit","edit\/post_delete","edit\/salon_add","edit\/salon_edit","edit\/salon_delete","edit\/post_banner_add","edit\/post_banner_edit","edit\/post_banner_delete","edit\/tags_add","edit\/tags_edit","edit\/tags_delete","edit\/blacklist_add","edit\/blacklist_edit","edit\/blacklist_delete","edit\/post_content_main_delete","edit\/post_content_photo_delete","edit\/post_content_selfie_delete","edit\/post_content_verify_delete","edit\/post_content_video_delete","edit\/location_city_add","edit\/location_city_edit","edit\/location_city_delete","edit\/location_zone_add","edit\/location_zone_edit","edit\/location_zone_delete","edit\/location_metro_add","edit\/location_metro_edit","edit\/location_metro_delete","edit\/information_news_add","edit\/information_news_edit","edit\/information_news_delete","edit\/information_banner_add","edit\/information_banner_edit","edit\/information_banner_delete","edit\/payment_code_add","edit\/payment_code_edit","edit\/payment_code_delete","edit\/users_add","edit\/users_edit","edit\/users_delete"]}']);
        UsersGroup::create(['name' => 'Модераторы', 'color' => '#007BFF', 'permission' => '{"access":["view\/admin_panel","view\/dashboard","view\/post_moderation","view\/salon_moderation","view\/post","view\/salon","view\/post_banner","view\/tags","view\/blacklist","view\/post_content_main","view\/post_content_photo","view\/post_content_selfie","view\/post_content_verify","view\/post_content_video","view\/location_city","view\/location_zone","view\/location_metro","view\/information_news","view\/information_banner","view\/users","view\/user_group"],"modify":["edit\/post_moderation","edit\/salon_moderation","edit\/post_add","edit\/post_edit","edit\/salon_add","edit\/salon_edit","edit\/post_banner_add","edit\/post_banner_edit","edit\/tags_add","edit\/tags_edit","edit\/blacklist_add","edit\/blacklist_edit","edit\/location_city_add","edit\/location_city_edit","edit\/location_zone_add","edit\/location_zone_edit","edit\/location_metro_add","edit\/location_metro_edit","edit\/information_news_add","edit\/information_news_edit","edit\/information_banner_add","edit\/information_banner_edit"]}']);
        UsersGroup::create(['name' => 'Пользователи', 'color' => '#808080', 'permission' => '{}']);

        Users::create(['balance' => 1000000000, 'login' => 'mainadmin', 'name' => 'Сергей', 'password' => Hash::make('252325'), 'type' => 'customer', 'user_group_id' => 1, 'email' => 'mainadmin@gmail.com', 'email_activate' => 1]);
        Users::create(['balance' => 1000000000, 'login' => 'admin', 'name' => 'Анатолий', 'password' => Hash::make('252325'), 'type' => 'customer', 'user_group_id' => 2, 'email' => 'admin@gmail.com', 'email_activate' => 1]);
        Users::create(['balance' => 1000000000, 'login' => 'moderator', 'name' => 'Ирина', 'password' => Hash::make('252325'), 'type' => 'customer', 'user_group_id' => 3, 'email' => 'moderator@gmail.com', 'email_activate' => 1]);
        Users::create(['balance' => 10000, 'login' => 'user', 'name' => 'Кристина', 'password' => Hash::make('252325'), 'type' => 'customer', 'user_group_id' => 4, 'email' => 'user@gmail.com', 'email_activate' => 1]);

        for ($i = 1; $i <= 5; $i++) {
            $email_uid = mb_strtolower($this->generateUniqueId('%s%s%s')).'@gmail.com';
            $login_uid = mb_strtolower($this->generateUniqueId('%s_%s%s'));
            $name_uid = mb_strtolower($this->generateUniqueId('%s%s_%s'));
            Users::create([
                'balance' => rand(1000, 20000),
                'login' => $login_uid,
                'name' => $name_uid,
                'password' => Hash::make('252325'),
                'type' => 'customer',
                'user_group_id' => 4,
                'email' => $email_uid,
                'email_activate' => 1,
                'created_at' => Carbon::now()->subDays(rand(0, 31)),
            ]);
        }
    }

    public function generateUniqueId($template = '%s_%s'): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $partsCount = substr_count($template, '%s');
        $parts = [];
        for ($i = 0; $i < $partsCount; $i++) {
            $parts[] = substr(str_shuffle($characters), 0, 4);
        }
        return vsprintf($template, $parts);
    }
}
