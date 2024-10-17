<?php

namespace Modules\Core\Entities\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User\PasswordReset;
use Modules\Core\Notifications\SendPasswordResetNotification;

trait ResetsPassword 
{ 
    /**
     * Get the password reset records for user
     *
     * @return HasMany
     */
    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class,
            'user_id');
    }

    /**
     * Send a forgot password email to user
     *
     * @return ResetsPassword
     * @throws Exception
     */
    public function sendForgotPasswordNotification()
    {
        /** @var PasswordReset $existing */
        $existing = $this->passwordResets()
            ->valid()->first();
        $this->notify(new SendPasswordResetNotification(
        // if a password reset record already exists for user
            $existing ?
                // first extend it then pass it to the email
                $existing->extend() :
                // or else, just create a new password reset record
                $this->createPasswordReset()
        ));

        return $this;
    }

    /**
     * Create a new password reset
     *
     * @return PasswordReset|Model
     * @throws Exception
     */
    public function createPasswordReset()
    {
        return $this->passwordResets()->create([
            'user_id' => $this->id,
            'expires_at' => now()->addMinutes(
                PasswordReset::DEFAULT_EXPIRY_IN_MINUTES
            ),
            'pin' => substr(crc32(random_bytes(32)),
                0, 4)
        ]);
    }

    /**
     * Update user's password with a new one
     *
     * @param $newPassword
     * @return bool
     */
    public function updatePassword($newPassword)
    {
        return $this->update([
            'password' => Hash::make($newPassword)
        ]);
    }
}