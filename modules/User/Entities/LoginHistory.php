<?php

namespace Modules\User\Entities;

use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Database\factories\LoginHistoryFactory;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class LoginHistory extends Model
{
    use HasFactory, HasEagerLimit;

    /**
     * Different login types
     */
    const LOGIN_TYPE_REGULAR = 'regular';
    const LOGIN_TYPE_ADMIN = 'admin';

    /**
     * Mass-assignable attributes
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type',
        'device', 'ip'
    ];

    /**
     * Create a new factory for model
     *
     * @return LoginHistoryFactory
     */
    protected static function newFactory()
    {
        return LoginHistoryFactory::new();
    }

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
     * Create a new record of login history for
     * the given user as a regular member
     *
     * @param User $user
     * @param string|null $device
     * @param string|null $ip
     * @return Model|LoginHistory
     */
    public static function createNormalLoginHistory(User $user, $device, $ip)
    {
        return $user->loginHistories()->create([
            'type' => self::LOGIN_TYPE_REGULAR,
            'device' => $device,
            'ip' => $ip
        ]);
    }

    /**
     * Create a new record of login history for
     * the given user as admin
     *
     * @param User $user
     * @param string|null $device
     * @param string|null $ip
     * @return Model|LoginHistory
     */
    public static function createAdminLoginHistory(User $user, $device, $ip)
    {
        return $user->loginHistories()->create([
            'type' => self::LOGIN_TYPE_ADMIN,
            'device' => $device,
            'ip' => $ip
        ]);
    }
}
