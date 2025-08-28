<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;

class Processing extends Model
{
    protected $table = 'ex_processing';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'key',
        'value'
    ];
}
