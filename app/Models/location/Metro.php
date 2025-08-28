<?php

namespace App\Models\location;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metro extends Model {
    use HasFactory;

    protected $table = 'ex_location_metro';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'city_id',
        'status'
    ];
}

