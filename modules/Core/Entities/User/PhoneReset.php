<?php

namespace Modules\Core\Entities\User;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Entities\Traits\UuidModelTrait;
use Modules\User\Entities\BlacklistedPhoneNumber;

/**
 * Class PhoneReset
 *
 * @package Labs\Core\Entities\User
 */
class PhoneReset extends Model
{
    use UuidModelTrait;

    /**
     * Default minutes of expiry and extend time for
     * password reset records
     */
    const DEFAULT_EXPIRY_IN_MINUTES = 4 * 60;
    const DEFAULT_EXTEND_IN_MINUTES = 4 * 60;

    /**
     * Disable auto-incrementing for the ID
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'expires_at',
        'used_at', 'new_phone_number',
    ];

    /**
     * Cast attributes
     *
     * @var string[]
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Get the associated user with password reset record
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class,
            'user_id');
    }

    /**
     * Apply the new phone number on the phone resets query
     *
     * @param Builder $query
     * @param string $phoneNumber
     * @return
     */
    public function scopePhoneNumber($query, $phoneNumber)
    {
        return $query->where('new_phone_number', $phoneNumber);
    }

    /**
     * Filter the password reset and
     * only return those who have not
     * expired yet
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
     * Check if password reset record
     * has already expired
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expires_at < now();
    }

    /**
     * Check if the record is used before
     *
     * @return bool
     */
    public function isUsed()
    {
        return (bool)$this->used_at;
    }

    /**
     * Mark the phone reset record as used
     *
     * @return bool
     */
    public function markAsUsed()
    {
        return $this->update([
            'used_at' => now()
        ]);
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

    /**
     * Apply the phone reset on the user
     *
     * @return bool
     */
    public function apply()
    {
        if (!($user = $this->user)) return false;

        BlacklistedPhoneNumber::createRecord($user,
            $user->phone_number);

        $this->markAsUsed();

        return $user->updatePhoneNumber(
            $this->new_phone_number
        );
    }

    /**
     * Get the URL to use phone reset record
     *
     * @return
     */
    public function getUrlAttribute()
    {
        return config('app.url')
            . '/api'
            . config('frontend.phone_reset_route')
            . "/$this->id";
    }
}