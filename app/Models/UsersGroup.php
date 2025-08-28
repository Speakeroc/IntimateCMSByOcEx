<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UsersGroup extends Model {
    use HasFactory;

    protected $table = 'ex_users_group';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'color',
        'permission',
        'created_at',
        'updated_at'
    ];

    public function checkAccess($type, $permission): ?RedirectResponse
    {
        if (!Auth::user()->can($type, $permission)) return redirect()->route('client.index');
        return null;
    }
}
