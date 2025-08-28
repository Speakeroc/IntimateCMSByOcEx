<?php

namespace App\Http\Controllers\catalog\auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassMail;
use App\Models\system\Getters;
use App\Models\Users;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class forgotController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function index()
    {
        SEOMeta::setTitle(__('catalog/auth.forgot_t'));

        $data['title'] = __('catalog/auth.forgot_t');
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/auth/forgot', ['data' => $data]);
    }

    public function post(Request $request): RedirectResponse
    {

        $this->validate($request, [
            'form_email' => 'required|email|max:255',
        ]);

        $email = $request->input('form_email');

        $user = Users::where('email', '=', $email)->first();
        $site_name = $this->getters->getSetting('micro_site_name') ?? 'Intimate CMS';

        $token = $this->getters->randomString(length: 40);

        if ($user['id']) {
            Users::where('email', $email)->update([
                'forgot_token' => $token
            ]);

            Mail::to($email)->send(new ForgotPassMail(base64_encode($user['id']), $token, $email, $site_name));
        }


        return redirect()->back()->with('success', __('catalog/auth.forgot_success'));
    }

    public function forgotLink($id, $token)
    {

        $user_id = base64_decode($id);

        $check = Users::where('id', $user_id)->where('forgot_token', $token)->first();

        if ($check) {
            $data['title'] = __('catalog/auth.forgot_success_title');
            $data['text'] = __('catalog/auth.forgot_success_txt');
            $data['forgot'] = true;
        } else {
            $data['title'] = __('catalog/auth.forgot_error_title');
            $data['text'] = __('catalog/auth.forgot_error_txt');
            $data['forgot'] = false;
        }
        SEOMeta::setTitle($data['title']);

        $data['id'] = $user_id;
        $data['token'] = $token;

        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/auth/forgot_link', ['data' => $data]);
    }

    public function forgotLinkPost(Request $request, $id, $token): ?RedirectResponse
    {

        $this->validate($request, [
            'password' => 'required|min:8|confirmed',
        ]);

        $check = Users::where('id', $id)->where('forgot_token', $token)->first();

        $password = $request->input('password');

        if ($check) {
            Users::where('id', $id)->where('forgot_token', $token)->update([
                'password' => bcrypt($password),
                'forgot_token' => ''
            ]);

            return redirect()->back()->with('accept', __('catalog/auth.notify_forgot_success', ['link' => route('client.auth.sign_in')]));
        } else {
            return redirect()->back()->with('error', __('catalog/auth.notify_forgot_error'));
        }
    }
}
