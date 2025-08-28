<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'ex_users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function user_group(): BelongsTo
    {
        return $this->belongsTo(UsersGroup::class);
    }

    public function can($abilities, $arguments = ''): bool {
        $group = $this->user_group;
        if (!is_array($abilities)) {
            $abilities = [$abilities];
        }
        $permissions = json_decode($group->permission, true);
        foreach ($abilities as $ability) {
            if (isset($permissions[$ability]) && in_array($arguments, $permissions[$ability])) {
                return true;
            }
        }
        return false;
    }
}
