<?php

namespace Modules\Core\Entities\User;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PasswordReset
 *
 * @package Labs\Core\Entities\User
 */
class PasswordReset extends Model
{
    /**
     * Default minutes of expiry and extend time for
     * password reset records
     */
    const DEFAULT_EXPIRY_IN_MINUTES = 15;
    const DEFAULT_EXTEND_IN_MINUTES = 10;

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'expires_at', 'pin',
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