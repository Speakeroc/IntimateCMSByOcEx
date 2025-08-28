<?php

namespace App\Models\location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model {
    use HasFactory;

    protected $table = 'ex_location_city';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'latitude',
        'longitude',
        'city_code',
        'status'
    ];
}

