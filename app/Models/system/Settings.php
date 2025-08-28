<?php

namespace App\Models\system;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'ex_settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'code',
        'key',
        'value'
    ];

    public function getSetting($code, $key): bool
    {
        $setting = Settings::where('code','=', $code)->where('key','=', $key)->first();
        if ($setting == null) $response = false;
        else $response = $setting->value;
        return $response;
    }
}
