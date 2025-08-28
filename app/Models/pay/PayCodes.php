<?php

namespace App\Models\pay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayCodes extends Model {
    use HasFactory;

    protected $table = 'ex_pay_codes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'pay_link',
        'nominal',
        'bonus',
        'status',
    ];
}

