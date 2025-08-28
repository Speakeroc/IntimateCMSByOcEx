<?php

namespace Database\Seeders;

use App\Models\posts\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run() {
        Category::create(['image' => '', 'title' => 'Индивидуалки', 'description' => '', 'meta_title' => '', 'meta_description' => '', 'slug' => 'individualki-ru', 'only_verify' => 1, 'status' => 1]);
        Category::create(['image' => '', 'title' => 'Couples', 'description' => '', 'meta_title' => '', 'meta_description' => '', 'slug' => 'couples-en', 'only_verify' => 0, 'status' => 1]);
    }
}
