<?php

namespace App\Models\Info;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model {
    use HasFactory;

    protected $table = 'ex_banner';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'banner',
        'link',
        'sort_order',
        'status',
    ];
}

