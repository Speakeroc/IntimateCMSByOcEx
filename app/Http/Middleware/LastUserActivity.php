<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LastUserActivity
{
    public function handle(Request $request, Closure $next) {
        if (Auth::check()) {
            Cache::remember('last_seen_update_'.Auth::user()->id, now()->addMinutes(5), function() {
                return User::where('id', Auth::user()->id)->update(['last_seen' => now()]);
            });
        }
        return $next($request);
    }
}
