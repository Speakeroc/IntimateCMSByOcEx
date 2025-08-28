<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebRealtime extends Model {
    use HasFactory;

    protected $table = 'ex_web_realtime';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'unique_code',
        'ip_address',
        'url',
        'created_at',
        'updated_at',
    ];
}

