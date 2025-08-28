<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class artisanController extends Controller
{
    public function runMigrations(): RedirectResponse
    {
        //Artisan::call('migrate:reset');
        //Artisan::call('migrate');
        //Artisan::call('db:seed');
        //Artisan::call('cache:clear');

        return redirect()->back()->with('success', 'Миграции успешно перезапущены и данные заполнены.');
    }
}
