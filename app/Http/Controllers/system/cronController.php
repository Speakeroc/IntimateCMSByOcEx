<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;

class cronController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function cronChecker() {
        $this->checkPostActivation(); //Проверка активированных анкет
    }

    public function checkPostActivation() {
        if ($this->getters->getSetting('post_activation_status')) {
            //ПРоверяет и отключаем просроченные анкеты
        }
    }
}
