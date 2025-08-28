<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $form_site_name;
    public string $form_theme;
    public string $form_name;
    public string $form_email;
    public string $form_message;

    public function __construct($theme, $name, $email, $message, $site_name)
    {
        $this->form_site_name = $site_name;
        $this->form_theme = $theme;
        $this->form_name = $name;
        $this->form_email = $email;
        $this->form_message = $message;
    }

    public function build(): ContactMail
    {
        return $this->subject('Обратная связь | '.$this->form_site_name)->view('mail.feedback');
    }
}
