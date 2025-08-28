<?php

namespace App\Models\pay;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayCodesPinCode extends Model {
    use HasFactory;

    protected $table = 'ex_pay_codes_pin_code';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'pay_id',
        'pin_code',
    ];
}

