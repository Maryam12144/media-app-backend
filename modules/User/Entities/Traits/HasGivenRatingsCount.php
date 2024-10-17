<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Libraries\Option;

trait HasGivenRatingsCount
{
    /**
     * Get the number of ratings given by user
     *
     * @return array
     */
    public function getGivenRatingsCountAttribute()
    {
        /*
         * Check if given ratings count has been cached for user
         */
        $cached = self::getGivenRatingsCountFromCache($this);

        /*
         * Return the ratings count from the cache if there
         * is a cached value
         */
        if (!is_null($cached)) return $cached;

        /*
         * Otherwise, get the number of ratings from
         * the database for user
         */
        $count = $this->ratingsGiven()
            ->count();

        /*
         * Then cache it for later use
         */
        self::storeGivenRatingsCountIntoCache($this,
            $count);

        return $count;
    }

    /**
     * Get the given ratings count from cache
     *
     * @param User|HasGivenRatingsCount|int $user
     * @return array|null
     */
    public static function getGivenRatingsCountFromCache($user)
    {
        return Cache::get(self::givenRatingsCountCacheKey($user));
    }

    /**
     * Store the given ratings count into the cache
     *
     * @param User|self|int $user
     * @param array $givenRatingsCount
     * @return bool
     */
    public static function storeGivenRatingsCountIntoCache($user, $givenRatingsCount)
    {
        return Cache::put(self::givenRatingsCountCacheKey($user),
            $givenRatingsCount,
            Option::globalCacheExpirationTime());
    }

    /**
     * Get the cache key for given ratings count
     *
     * @param User|int
     * @return string
     */
    public static function givenRatingsCountCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "given_ratings_count:$user";
    }

    /**
     * Reset user's given ratings count cache
     *
     * @param User|int
     * @return bool
     */
    public static function resetGivenRatingsCountCache($user)
    {
        return Cache::forget(self::givenRatingsCountCacheKey($user));
    }
}