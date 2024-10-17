<?php

namespace Modules\Core\Entities\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Entities\User\PhoneReset;
use Modules\Core\Notifications\SendPhoneResetNotification;

trait ResetsPhone
{
    /**
     * Get the phone reset records for user
     *
     * @return HasMany
     */
    public function phoneResets()
    {
        return $this->hasMany(PhoneReset::class,
            'user_id');
    }

    /**
     * Send a forgot phone email to user
     *
     * @param string $newPhoneNumber
     * @return ResetsPhone
     * @throws Exception
     */
    public function sendResetPhoneNotification($newPhoneNumber)
    {
        /** @var PhoneReset $existing */
        $existing = $this->phoneResets()
            ->phoneNumber($newPhoneNumber)
            ->valid()
            ->first();

        $this->notify(new SendPhoneResetNotification(
        // if a phone reset record already exists for user
            $existing ?
                // first extend it then pass it to the email
                $existing->extend() :
                // or else, just create a new phone reset record
                $this->createPhoneReset($newPhoneNumber)
        ));

        return $this;
    }

    /**
     * Create a new phone reset
     *
     * @param string $newPhoneNumber
     * @return PhoneReset|Model
     * @throws Exception
     */
    public function createPhoneReset($newPhoneNumber)
    {
        return $this->phoneResets()->create([
            'user_id' => $this->id,
            'expires_at' => now()->addMinutes(
                PhoneReset::DEFAULT_EXPIRY_IN_MINUTES
            ),
            'new_phone_number' => $newPhoneNumber,
        ]);
    }

    /**
     * Update user's phone with a new one
     *
     * @param string $newPhoneNumber
     * @return bool
     */
    public function updatePhoneNumber($newPhoneNumber)
    {
        return $this->update([
            'phone_number' => $newPhoneNumber
        ]);
    }
}