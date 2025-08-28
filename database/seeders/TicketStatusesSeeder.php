<?php

namespace Database\Seeders;

use App\Models\system\TicketStatuses;
use Illuminate\Database\Seeder;

class TicketStatusesSeeder extends Seeder
{
    public function run() {
        TicketStatuses::create(['name' => 'Открыт']);
        TicketStatuses::create(['name' => 'Закрыт']);
    }
}
