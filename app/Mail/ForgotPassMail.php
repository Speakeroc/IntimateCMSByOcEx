<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $form_link;
    public string $form_email;
    public string $form_index;
    public string $site_name;

    public function __construct($id, $token, $email, $site_name)
    {
        $this->form_link = route('client.auth.forgot_link', ['id' => $id, 'token' => $token]);
        $this->form_email = $email;
        $this->form_index = route('client.index');
        $this->site_name = $site_name;
    }

    public function build(): ForgotPassMail
    {
        return $this->subject('Восстановление пароля')->view('mail.forgot');
    }
}
