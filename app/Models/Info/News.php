<?php

namespace App\Models\Info;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model {
    use HasFactory;

    protected $table = 'ex_news';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'desc',
        'meta_title',
        'meta_description',
        'image',
        'seo_url',
        'views',
        'pinned',
        'like',
        'dislike',
        'status',
        'created_at',
    ];
}

