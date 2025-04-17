<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class HelpRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $problem;

    public function __construct(User $user, string $problem)
    {
        $this->user = $user;
        $this->problem = $problem;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('2FA Segítségkérés - ' . $this->user->name)
                    ->view('emails.help-request');
    }
}
