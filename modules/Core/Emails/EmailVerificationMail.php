<?php

namespace Modules\Core\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\User\EmailVerification;
use Modules\Core\Entities\User\PhoneReset;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $pin;
    // public $url;

    /**
     * @var User
     */
    public $user;

    /**
     * Expiry time in minutes
     *
     * @var int
     */
    public $expiry = EmailVerification::DEFAULT_EXPIRY_IN_HOURS;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $url
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
        return $this->markdown('emails.verify-email', [
            'pin' => $this->pin,
            'expiry' => $this->expiry
        ]);
    }
}
