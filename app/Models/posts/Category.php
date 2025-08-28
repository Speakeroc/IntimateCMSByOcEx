<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory;

    protected $table = 'ex_posts_category';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'image',
        'title',
        'description',
        'meta_title',
        'meta_description',
        'slug',
        'only_verify',
        'status'
    ];
}

