<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
    protected $table = 'ex_tickets';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'subject',
        'status_id',
        'view_admin',
        'view_user'
    ];
}
