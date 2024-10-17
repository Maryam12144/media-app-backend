<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Libraries\Option;

trait CachesUser
{
    /**
     * Get user from cache
     *
     * @param User|int $user
     * @return User|null
     */
    public static function getUserFromCache($user)
    {
        return Cache::remember(self::userCacheKey($user),
            Option::globalCacheExpirationTime(), function () use ($user) {
                if (!($user instanceof User)) {
                    $user = User::find($user);
                }

                return $user;
            });
    }

    /**
     * Get the cache key for user
     *
     * @param User|int $user
     * @return string
     */
    public static function userCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "user:$user";
    }

    /**
     * Reset the cache for user
     *
     * @param User|int
     * @return bool
     */
    public static function resetUserCache($user)
    {
        return Cache::forget(
            self::userCacheKey($user)
        );
    }
}