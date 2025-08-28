<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsRateHistory extends Model {
    use HasFactory;

    protected $table = 'ex_news_rate_history';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'news_id',
        'sign',
        'type',
        'created_at',
    ];
}

