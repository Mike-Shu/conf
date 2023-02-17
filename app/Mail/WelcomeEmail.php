<?php

namespace App\Mail;

use App\Settings\TenantSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return WelcomeEmail
     */
    public function build(): WelcomeEmail
    {
        $title = app(TenantSettings::class)->title;
        $address = config('mail.from.address');
        $subject = 'Вы успешно зарегистрировались';

        return $this->view('emails.welcome')
            ->from($address, $title)
            ->cc($address, $title)
            ->bcc($address, $title)
            ->replyTo($address, $title)
            ->subject($subject)
            ->with([
                'title' => $title,
                'email' => $this->data['email'],
                'password' => $this->data['password'],
                'login_url' => $this->data['login_url'],
            ]);
    }
}
