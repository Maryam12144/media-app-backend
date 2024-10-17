<?php

namespace Modules\Core\Entities\Traits;

use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Entities\User\EmailVerification;
use Modules\Core\Notifications\SendEmailVerificationNotification;
use Modules\Core\Notifications\SendGeneratedPasswordNotification;

trait VerifiesEmail
{
    /**
     * Get the email verifications created for user
     *
     * @return HasMany
     */
    public function emailVerifications()
    {
        return $this->hasMany(EmailVerification::class);
    }

    /**
     * Check if user has already verified email
     *
     * @return bool|void
     */
    public function hasVerifiedEmail()
    {
        return (bool)$this->email_verified_at;
    }

    /**
     * Send a verification email to user
     *
     * @return VerifiesEmail
     * @throws Exception
     */
    public function sendEmailVerificationNotification()
    {
        /** @var EmailVerification $existing */
        $existing = $this->emailVerifications()
            ->valid()->first();

        $this->notify(new SendEmailVerificationNotification(
        // if a password reset record already exists for user
            $existing ?
                // first extend it by the default amount of time then pass it to the email
                $existing->extend() :
                // or else, just create a new email verification record
                $this->createEmailVerification()
        ));

        return $this;
    }

    /**
     * Create a new email verification record
     *
     * @return EmailVerification|Model
     * @throws Exception
     */
    public function createEmailVerification()
    {
        return $this->emailVerifications()->create([
            'user_id' => $this->id,
            'expires_at' => now()->addMinutes(
                EmailVerification::DEFAULT_EXPIRY_IN_MINUTES
            ),
            'token' => md5(random_bytes(64))
        ]);
    }

    /**
     * Set user's email verification status to true
     *
     * @return bool
     */
    public function markEmailAsVerified($status = true)
    {
        return $this->update([
            'email_verified_at' => $status
                ? now() : null
        ]);
    }
}