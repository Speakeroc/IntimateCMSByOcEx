<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'ex_feedback';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'theme',
        'name',
        'email',
        'message',
    ];
}
