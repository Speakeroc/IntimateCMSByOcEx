<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostContent extends Model {
    use HasFactory;

    protected $table = 'ex_post_content';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'file',
        'type'
    ];
}

