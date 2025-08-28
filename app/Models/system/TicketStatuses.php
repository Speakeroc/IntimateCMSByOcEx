<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;

class TicketStatuses extends Model
{
    protected $table = 'ex_ticket_statuses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name'
    ];
}
