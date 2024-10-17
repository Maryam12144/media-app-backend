<?php

namespace Modules\Core\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\User\PhoneReset;

class PhoneResetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var PhoneReset
     */
    public $phoneReset;

    /**
     * @var User
     */
    public $user;

    /**
     * @var int
     */
    public $expiry = PhoneReset::DEFAULT_EXPIRY_IN_MINUTES;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param PhoneReset $phoneReset
     * @param int $expiry
     */
    public function __construct($user, $phoneReset, $expiry = null)
    {
        $this->phoneReset = $phoneReset;
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
        return $this->markdown('emails.phone-reset', [
            'phoneNumber' => $this->phoneReset->new_phone_number,
            'expiry' => $this->expiry,
            'url' => $this->phoneReset->url
        ]);
    }
}
