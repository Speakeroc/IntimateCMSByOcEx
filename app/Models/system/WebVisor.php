<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebVisor extends Model {
    use HasFactory;

    protected $table = 'ex_web_visor';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'unique_code',
        'ip_address',
        'browser',
        'language',
        'device',
        'country',
        'operating_system',
        'source',
        'visited_at',
        'latitude',
        'longitude',
        'visit_count',
        'created_at',
        'updated_at',
    ];
}

