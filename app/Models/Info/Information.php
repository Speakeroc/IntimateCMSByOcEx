<?php

namespace App\Models\Info;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model {
    use HasFactory;

    protected $table = 'ex_information';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'desc',
        'meta_title',
        'meta_description',
        'seo_url',
        'views',
        'status',
        'in_menu',
        'created_at',
    ];
}

