<?php

namespace App\Models\posts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalonContent extends Model {
    use HasFactory;

    protected $table = 'ex_salon_content';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'salon_id',
        'user_id',
        'file',
        'type'
    ];
}

