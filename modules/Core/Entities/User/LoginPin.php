<?php

namespace Modules\Core\Entities\User;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Notification\Libraries\Twilio;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;

/**
 * Class LoginPin
 *
 * @package Labs\Core\Entities\User
 */
class LoginPin extends Model
{
    /**
     * Default minutes of expiry and extend time for
     * login pin records
     */
    const DEFAULT_EXPIRY_IN_MINUTES = 15;
    const DEFAULT_EXTEND_IN_MINUTES = 10;
    const PIN_LENGTH = 4;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'phone_number', 'expires_at', 'pin'
    ];

    /**
     * Cast attributes
     *
     * @var string[]
     */
    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * On-boot model hooks
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::saving(function (LoginPin $loginPin) {
            if (!$loginPin->pin) {
                $randomPin = '';

                for ($i = 0; $i < self::PIN_LENGTH; $i++) {
                    $randomPin .= rand(0, 9);
                }

                $loginPin->pin = $randomPin;
            }

            if (!$loginPin->expires_at) {
                $loginPin->expires_at = now()->addMinutes(
                    self::DEFAULT_EXPIRY_IN_MINUTES
                );
            }
        });
    }

    /**
     * Send the login pin via SMS to the phone number
     *
     * @return MessageInstance
     * @throws TwilioException
     */
    public function sendViaSms()
    {
        return Twilio::instance()
            ->message(
                $this->phone_number,
                __('sms.login-pin', [
                    'pin' => $this->pin
                ])
            );
    }

    /**
     * Generate a login pin record for the given phone number
     *
     * @param string $phoneNumber
     * @return self|Model
     */
    public static function generate($phoneNumber)
    {
        return self::query()
            ->create([
                'phone_number' => $phoneNumber,
            ]);
    }

    /**
     * Get the associated user with login pin record
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,
            'phone_number', 'phone_number');
    }

    /**
     * Apply phone number on the login pin query
     *
     * @param Builder $query
     * @param string $phoneNumber
     * @return Builder
     */
    public function scopePhoneNumber($query, $phoneNumber)
    {
        return $query->where('phone_number',
            $phoneNumber);
    }

    /**
     * Apply recent status on the query for only fetch
     * those that are created in the past 2 minutes
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>',
            now()->subMinutes(2));
    }

    /**
     * Filter the login pin and only return those
     * who have not expired yet
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at',
            '>', now());
    }

    /**
     * Check if login pin record
     * has already expired
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expires_at < now();
    }

    /**
     * Extend the forgot password code
     *
     * @param int $minutes
     * @return self
     */
    public function extend($minutes = null)
    {
        $this->update([
            'expires_at' => now()->addMinutes(
                $minutes ?: self::DEFAULT_EXTEND_IN_MINUTES
            )
        ]);

        return $this;
    }
}