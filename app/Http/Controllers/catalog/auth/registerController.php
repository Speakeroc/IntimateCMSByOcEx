<?php

namespace App\Http\Controllers\catalog\auth;

use App\Http\Controllers\Controller;
use App\Mail\RegisterVerifyEmailMail;
use App\Models\Info\Information;
use App\Models\system\Getters;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class registerController extends Controller
{
    private Getters $getters;

    public function __construct() {
        $this->getters = new Getters;
    }

    public function index()
    {
        SEOMeta::setTitle(__('catalog/page_titles.sign_up'));

        $privacy = $this->getters->getSetting('reg_privacy');

        $information = Information::where('id', $privacy)->first();
        $info_link = (isset($information) && !empty($information)) ? route('client.information', ['info_id' => $information['id'], 'title' => Str::slug($information['title'])]) : '';
        $info_title = (isset($information) && !empty($information)) ? $information['title'] : '';

        $data['privacy'] = __('catalog/auth.reg_privacy', ['link' => $info_link, 'title' => $info_title]);

        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/auth/register', ['data' => $data]);
    }

    public function post(Request $request): RedirectResponse {
        $this->validate($request, [
            'login' => 'required|min:4|max:20|unique:ex_users|regex:/^[a-zA-Z0-9]+$/',
            'name' => 'required|min:2|max:20',
            'email' => 'required|unique:ex_users|email|max:255',
            'password' => 'required|min:8|confirmed',
            'privacy' => 'required',
        ]);

        $token = $this->getters->randomString(length: 40);
        $login = $request->input('login');
        $name = $request->input('name');
        $password = $request->input('password');
        $email = $request->input('email');

        $auth_email_verify = $this->getters->getSetting('auth_email_verify') ?? null;
        $reg_start_balance = (int)$this->getters->getSetting('reg_start_balance') ?? 0;
        $site_name = $this->getters->getSetting('micro_site_name') ?? 'Intimate CMS';

        $user = Users::create([
            'balance' => $reg_start_balance,
            'login' => $login,
            'name' => $name,
            'password' => bcrypt($password),
            'type' => 'customer',
            'user_group_id' => 4,
            'email' => $email,
            'email_activate' => ($auth_email_verify) ? 0 : 1,
            'email_activate_code' => $token,
        ]);

        $user_id = $user->id;

        if ($user_id && $auth_email_verify) {
            Mail::to($email)->send(new RegisterVerifyEmailMail(base64_encode($user_id), $token, $email, $site_name));
        }

        return redirect()->route('client.auth.register_finish');
    }

    public function register_finish()
    {
        SEOMeta::setTitle(__('catalog/auth.reg_finish_title'));

        $auth_email_verify = $this->getters->getSetting('auth_email_verify') ?? null;

        if ($auth_email_verify) {
            $data['title'] = __('catalog/auth.reg_finish_title');
            $data['text'] = __('catalog/auth.reg_finish_text_verify');
        } else {
            $data['title'] = __('catalog/auth.reg_finish_title');
            $data['text'] = __('catalog/auth.reg_finish_text_no_verify');
        }

        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/auth/activate', ['data' => $data]);
    }

    public function activate($id, $token)
    {
        $user_id = base64_decode($id);

        $check = Users::where('id', $user_id)->where('email_activate', 0)->where('email_activate_code', $token)->first();

        if ($check) {
            Users::where('id', $user_id)->update(['email_activate' => 1, 'email_activate_code' => '', 'email_activate_time' => Carbon::now()]);
            $data['title'] = __('catalog/auth.reg_activate_success_t');
            $data['text'] = __('catalog/auth.reg_activate_success_txt');
        } else {
            $data['title'] = __('catalog/auth.reg_activate_error_t');
            $data['text'] = __('catalog/auth.reg_activate_error_txt');
        }

        SEOMeta::setTitle($data['title']);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/auth/activate', ['data' => $data]);
    }
}
