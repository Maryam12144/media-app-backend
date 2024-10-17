<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Libraries\Option;
use Modules\Rating\Entities\Rating;

trait HasRateLock
{
    /**
     * Get the rating lock for the given user from the cache
     *
     * @param User|int $user
     * @return bool
     */
    public static function getUserRateLockFromCache($user)
    {
        return Cache::get(self::userRateLockCacheKey($user));
    }

    /**
     * Store a rating lock for the given user into the cache
     *
     * @param User|int $user
     * @return bool
     */
    public static function storeUserRateLockIntoCache($user)
    {
        return Cache::put(self::userRateLockCacheKey($user), 1,
            self::USER_RATING_LOCK_IN_SECONDS);
    }

    /**
     * Reset the rating lock cache for the given user
     *
     * @param User|int $user
     * @return bool
     */
    public static function resetUserRateLockCache($user)
    {
        return Cache::forget(self::userRateLockCacheKey($user));
    }

    /**
     * Get the cache key for the rating lock of the given user
     *
     * @param User|int
     * @return string
     */
    public static function userRateLockCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "user_rate_lock:$user";
    }
}