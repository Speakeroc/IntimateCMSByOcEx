<?php

namespace App\Http\Controllers\system;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DarkModeController extends Controller
{
    public function toggle(Request $request): JsonResponse
    {
        $request->session()->put('darkMode', $request->input('darkMode'));
        return response()->json(['status' => 'success']);
    }
}
