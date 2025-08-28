<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;

class TicketMessages extends Model
{
    protected $table = 'ex_ticket_messages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'ticket_id',
        'user_id',
        'content',
        'is_admin'
    ];
}
