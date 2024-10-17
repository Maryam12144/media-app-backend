<?php

namespace Modules\Core\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\User\PasswordReset;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var PasswordReset
     */
    public $pin;

    /**
     * @var User
     */
    public $user;

    /**
     * @var int
     */
    public $expiry = PasswordReset::DEFAULT_EXPIRY_IN_MINUTES;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param int|string $pin
     * @param int $expiry
     */
    public function __construct($user, $pin, $expiry = null)
    {
        $this->pin = $pin;
        $this->user = $user;

        if ($expiry) {
            $this->expiry = $expiry;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forgot-password', [
            'pin' => $this->pin,
            'expiry' => $this->expiry
        ]);
    }
}
