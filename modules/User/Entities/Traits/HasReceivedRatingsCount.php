<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Libraries\Option;

trait HasReceivedRatingsCount
{
    /**
     * Get the number of ratings received by user
     *
     * @return array
     */
    public function getReceivedRatingsCountAttribute()
    {
        /*
         * Check if received ratings count has been cached for user
         */
        $cached = self::getReceivedRatingsCountFromCache($this);

        /*
         * Return the ratings count from the cache if there
         * is a cached value
         */
        if (!is_null($cached)) return $cached;

        /*
         * Otherwise, get the number of ratings from
         * the database for user
         */
        $count = $this->ratingsReceived()
            ->count();

        /*
         * Then cache it for later use
         */
        self::storeReceivedRatingsCountIntoCache($this,
            $count);

        return $count;
    }

    /**
     * Get the received ratings count from cache
     *
     * @param User|HasReceivedRatingsCount|int $user
     * @return array|null
     */
    public static function getReceivedRatingsCountFromCache($user)
    {
        return Cache::get(self::receivedRatingsCountCacheKey($user));
    }

    /**
     * Store the received ratings count into the cache
     *
     * @param User|self|int $user
     * @param array $receivedRatingsCount
     * @return bool
     */
    public static function storeReceivedRatingsCountIntoCache($user, $receivedRatingsCount)
    {
        return Cache::put(self::receivedRatingsCountCacheKey($user),
            $receivedRatingsCount,
            Option::globalCacheExpirationTime());
    }

    /**
     * Get the cache key for received ratings count
     *
     * @param User|int
     * @return string
     */
    public static function receivedRatingsCountCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "received_ratings_count:$user";
    }

    /**
     * Reset user's received ratings count cache
     *
     * @param User|int
     * @return bool
     */
    public static function resetReceivedRatingsCountCache($user)
    {
        return Cache::forget(self::receivedRatingsCountCacheKey($user));
    }
}