<?php

namespace App\Models\location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model {
    use HasFactory;

    protected $table = 'ex_location_zone';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'city_id',
        'status'
    ];
}

