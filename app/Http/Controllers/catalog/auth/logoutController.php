<?php

namespace App\Http\Controllers\catalog\auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class logoutController extends Controller
{
    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('client.index');
    }
}
