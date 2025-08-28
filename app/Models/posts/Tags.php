<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model {
    use HasFactory;

    protected $table = 'ex_post_tags';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'tag',
    ];
}

