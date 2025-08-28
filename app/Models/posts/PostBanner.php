<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostBanner extends Model {
    use HasFactory;

    protected $table = 'ex_post_banner';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'post_id',
        'banner',
        'activation',
        'activation_date',
        'up_date',
        'status',
        'created_at',
    ];
}

