<?php

namespace App\Http\Controllers\catalog\info;

use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\system\Feedback;
use Illuminate\Http\Request;
use App\Models\system\Getters;
use App\Models\system\ImageConverter;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class contactController extends Controller
{
    private Getters $getters;

    public function __construct()
    {
        $this->getters = new Getters;
        $this->imageConverter = new ImageConverter;
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        $data['h1'] = $title = __('catalog/page_titles.contact_h1');
        $data['title'] = $title = __('catalog/page_titles.contact_t');
        $description = __('catalog/page_titles.contact_d');
        $this->getters->setMetaInfo(title: $title, description: $description, url: route('client.contact'));

        if (Auth::check()) {
            $data['user_id'] = Auth::id();
            $data['user_name'] = Auth::user()->name;
        } else {
            $data['user_id'] = 0;
            $data['user_name'] = null;
        }


        $breadcrumbs = [
            ['link' => route('client.contact'), 'title' => __('catalog/page_titles.contact_t')],
        ];
        $data['breadcrumb'] = $this->getters->breadcrumbPages($breadcrumbs);
        $data['elements'] = $this->getters->getHeaderFooter();

        return view('catalog/info/contact', ['data' => $data]);
    }

    public function post(Request $request): RedirectResponse
    {

        $this->validate($request, [
            'form_theme' => 'required|min:5|max:100',
            'form_name' => 'required|min:6',
            'form_email' => 'required|email|max:255',
            'form_message' => 'required|min:50'
        ]);

        $user_id = $request->input('user_id');
        $form_theme = $request->input('form_theme');
        $form_name = $request->input('form_name');
        $form_email = $request->input('form_email');
        $form_message = $request->input('form_message');

        Feedback::create([
            'user_id' => $user_id,
            'theme' => $form_theme,
            'name' => $form_name,
            'email' => $form_email,
            'message' => $form_message,
        ]);

        $site_name = $this->getters->getSetting('micro_site_name');
        $support_email = $this->getters->getSetting('support_email');

        if ($support_email) {
            Mail::to($support_email)->send(new ContactMail($form_theme, $form_name, $form_email, $form_message, $site_name));
        }

        return redirect()->back()->with('success', __('catalog/info/contact.notify_message_send'));
    }
}
