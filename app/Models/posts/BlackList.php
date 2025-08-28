<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlackList extends Model {
    use HasFactory;

    protected $table = 'ex_client_blacklist';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'phone',
        'text',
        'rating',
        'user_id',
        'views',
        'created_at'
    ];
}

