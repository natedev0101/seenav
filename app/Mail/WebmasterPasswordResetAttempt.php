<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebmasterPasswordResetAttempt extends Mailable
{
    use Queueable, SerializesModels;

    public $targetUser;
    public $initiator;
    public $ipAddress;

    /**
     * Create a new message instance.
     */
    public function __construct(User $targetUser, User $initiator, $ipAddress)
    {
        $this->targetUser = $targetUser;
        $this->initiator = $initiator;
        $this->ipAddress = $ipAddress;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.webmaster-password-reset-plain')
                    ->subject('⚠️ Jelszó visszaállítási kísérlet - SeeNAV')
                    ->with([
                        'targetUser' => $this->targetUser,
                        'initiator' => $this->initiator,
                        'ipAddress' => $this->ipAddress,
                    ]);
    }
}
