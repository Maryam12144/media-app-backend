<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Libraries\Option;
use Modules\Rating\Entities\Rating;

trait HasTrustScore
{
    /**
     * Get the trust score attribute
     *
     * @return double
     */
    public function getTrustScoreAttribute()
    {
        /*
         * Check if trust score has been cached for user
         */
        $cached = self::getTrustScoreFromCache($this);

        /*
         * Return the score from the cache if there is a cached value
         */
        if ($cached) return (double)$cached;

        /*
         * Otherwise, re-calculate the trust score
         * and store to the cache for later use
         */
        return $this->recalculateTrustScore();
    }

    /**
     * Calculate trust score based on the reviews
     *
     * @return double
     */
    protected function recalculateTrustScore()
    {
        if (
            // if the minimum limit for number of ratings is enabled
            ($minimum = Option::minimumRatingToDisplayTrustScore())
            &&
            // and the number of ratings that user has received isn't sufficient
            $this->ratingsReceived()->count() < $minimum
        ) {
            // return empty
            return null;
        }

        $this->ratingsReceived()
            ->with([
                'items.quality'
            ])
            ->chunk(100, function ($ratings) use (&$ratingsCount, &$ratingsSum) {
                $ratingsCount = $ratingsCount ?? 0;
                $ratingsSum = $ratingsSum ?? 0;

                /**
                 * @var Rating[]|Collection $ratings
                 */
                foreach ($ratings as $rating) {
                    $ratingItems = $rating->items;

                    foreach ($ratingItems as $item) {
                        $quality = $item->quality;

                        $ratingsCount += $quality->weight;

                        $ratingsSum += ($item->score * $quality->weight);
                    }
                }
            });

        if (!$ratingsCount) {
            $score = null;
        } else {
            $score = $ratingsSum / $ratingsCount;
        }

        /*
         * And then store the calculated value to
         * the cache for later use
         */
        $this->storeTrustScoreIntoCache($this, $score);

        return $score;
    }

    /**
     * Get the trust score from cache
     *
     * @param User|int|HasTrustScore $user
     * @return string|null
     */
    public static function getTrustScoreFromCache($user)
    {
        return Cache::tags(['trust_score'])
            ->get(self::trustScoreCacheKey($user));
    }

    /**
     * Get the trust score from cache
     *
     * @param User|int|HasTrustScore $user
     * @param double $score
     * @return bool
     */
    public static function storeTrustScoreIntoCache($user, $score)
    {
        return Cache::tags(['trust_score'])
            ->put(self::trustScoreCacheKey($user), $score,
                Option::globalCacheExpirationTime());
    }

    /**
     * Get the cache key for trust score
     *
     * @param User|int
     * @return string
     */
    public static function trustScoreCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "trust_score:$user";
    }

    /**
     * Reset user's trust score cache
     *
     * @param User|int
     * @return bool
     */
    public static function resetTrustScoreCache($user)
    {
        return Cache::tags(['trust_score'])
            ->forget(self::trustScoreCacheKey($user));
    }

    /**
     * Reset all of the trust scores from the cache
     *
     * @return bool
     */
    public static function resetAllTrustScoresCache()
    {
        return Cache::tags(['trust_score'])
            ->flush();
    }
}