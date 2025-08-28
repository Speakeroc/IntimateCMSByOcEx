<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UsersSeeder::class,
            TagsSeeder::class,
            SettingsSeeder::class,
            CategorySeeder::class,
            CitySeeder::class,
            ZoneSeeder::class,
            MetroSeeder::class,
            PaySeeder::class,
            BlackListSeeder::class,
            SalonSeeder::class,
            PostSeeder::class,
            NewsSeeder::class,
            InformationSeeder::class,
            BannerSeeder::class,
            PostBannerSeeder::class,
            PostReviewsSeeder::class,
            TicketStatusesSeeder::class,
        ]);
    }
}
