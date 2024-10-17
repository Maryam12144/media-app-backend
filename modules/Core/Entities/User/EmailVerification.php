<?php

namespace Modules\Core\Entities\User;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class EmailVerification
 * @package Labs\Core\Entities
 */
class EmailVerification extends Model
{
    /**
     * Default minutes of expiry time for
     * email verification records
     *
     * (24 Hours * 60 Minutes) = 1 Whole Day
     */
    const DEFAULT_EXPIRY_IN_MINUTES = 24 * 60;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token', 'expires_at'
    ];

    /**
     * Custom-cast attributes
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Get the associated user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Filter the email verifications and
     * only return those who have not expired yet
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
     * Check if email verification record
     * has already expired
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expires_at < now();
    }

    /**
     * Fetch email verification
     *
     * @param int $id
     * @param string $token
     * @return null|EmailVerification
     */
    public static function fetch($id, $token)
    {
        return self::where([
            'id' => $id,
            'token' => $token
        ])->first();
    }

    /**
     * Extend the email verification code
     *
     * @param int $minutes
     * @return self
     */
    public function extend($minutes = null)
    {
        $this->update([
            'expires_at' => now()->addMinutes(
                $minutes ?: self::DEFAULT_EXPIRY_IN_MINUTES
            )
        ]);

        return $this;
    }

    /**
     * Get the url attribute of email verification
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return config('frontend.url')
            . config('frontend.email_verify_route')
            . "?code={$this->combined_code}";
    }

    /**
     * Get the verification code combined
     * with verification record ID
     *
     * @return string
     */
    public function getCombinedCodeAttribute()
    {
        return "$this->id|$this->token";
    }
}
