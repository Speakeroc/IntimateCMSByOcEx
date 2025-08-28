<?php

namespace App\Http\Controllers\catalog\auth;

use App\Http\Controllers\Controller;
use App\Models\system\Getters;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class loginController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function index()
    {
        SEOMeta::setTitle(__('catalog/page_titles.sign_in'));

        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/auth/login', ['data' => $data]);
    }

    public function post(Request $request): RedirectResponse {

        $this->validate($request, [
            'email_or_login' => 'required|max:255',
            'password' => 'required'
        ]);

        $login_type = filter_var($request->input('email_or_login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'login';

        $request->merge([
            $login_type => $request->input('email_or_login')
        ]);

        $credentials = $request->only([$login_type, 'password']);

        if (Auth::attempt($credentials, true)) {
            return redirect()->route('client.index')->with('success', __('catalog/auth.notify_success_login'));
        } else {
            return redirect()->back()->with('error', __('catalog/auth.notify_invalid_login'));
        }
    }

    public function popup(Request $request): JsonResponse
    {
        $login_type = filter_var($request->input('email_or_login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'login';

        $request->merge([
            $login_type => $request->input('email_or_login')
        ]);

        $credentials = $request->only([$login_type, 'password']);

        if (Auth::attempt($credentials, true)) {
            return response()->json(['success' => true, 'message' => __('catalog/auth.notify_success_login')]);
        } else {
            return response()->json(['success' => false, 'message' => __('catalog/auth.notify_invalid_login')]);
        }
    }
}
