<?php

namespace Modules\User\Entities\Traits;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Access\Entities\AccessRequest;
use Modules\Core\Libraries\Option;
use Modules\Quality\Entities\Quality;
use Modules\Rating\Entities\RatingItem;

trait HasRatedQualities
{
    /**
     * Get the qualities that user has been rated for
     *
     * @return Builder|Quality
     */
    public function ratedQualities()
    {
        return RatingItem::query()
            ->targetUser($this)
            ->select([
                'quality',
                DB::raw('avg(score) as average_rating'),
                DB::raw('count(id) as ratings_count'),
            ])
            ->groupBy('quality');
    }

    /**
     * Get the rated qualities of the user that are
     * accessible by the given user
     *
     * @param User $user
     * @return array
     */
    public function getAccessibleRatedQualitiesByUser($user)
    {
        $status = AccessRequest::accessRequestStatus($user,
            $this);

        if (
            // if user is fetching himself
            $this->id == $user->id
            ||
            // or he has access to the desired member's profile
            $status == AccessRequest::STATUS_ACCEPTED) {
            return $this->rated_qualities;
        }

        return $this->top_rated_qualities;
    }

    /**
     * Get all of the rated qualities of user
     *
     * @return array
     */
    public function getRatedQualitiesAttribute()
    {
        /*
         * Check if trust score has been cached for user
         */
        $cached = self::getRatedQualitiesFromCache($this);

        /*
         * Return the score from the cache if there is a cached value
         */
        if ($cached) return $cached;

        /*
         * Otherwise, re-calculate the trust score
         * and store to the cache for later use
         */
        return $this->recalculateRatedQualities();
    }

    /**
     * Calculate rated qualities based on the reviews
     *
     * @return array
     */
    protected function recalculateRatedQualities()
    {
        $ratedQualityItems = $this->ratedQualities()
            ->get();
        $formattedQualities = [];

        foreach ($ratedQualityItems as $item) {
            $item = [
                'quality' => $item->quality,
                'ratings_count' => $item->ratings_count,
                'average_rating' => $item->average_rating
            ];

            $formattedQualities[] = (object)$item;
        }
        usort($formattedQualities, function ($a, $b) {
            return $a->average_rating < $b->average_rating;
        });

        /*
         * And then store the calculated value to
         * the cache for later use
         */
        $this->storeRatedQualitiesIntoCache($this, $formattedQualities);

        return $formattedQualities;
    }

    /**
     * Get the trust score from cache
     *
     * @param User|HasRatedQualities|int $user
     * @return array|null
     */
    public static function getRatedQualitiesFromCache($user)
    {
        return Cache::tags(['user_rated_qualities'])
            ->get(self::ratedQualitiesCacheKey($user));
    }

    /**
     * Store the rated qualities into the cache
     *
     * @param User|self|int $user
     * @param array $ratedQualities
     * @return bool
     */
    public static function storeRatedQualitiesIntoCache($user, $ratedQualities)
    {
        return Cache::tags(['user_rated_qualities'])
            ->put(self::ratedQualitiesCacheKey($user), $ratedQualities,
                Option::globalCacheExpirationTime());
    }

    /**
     * Get the cache key for trust score
     *
     * @param User|int
     * @return string
     */
    public static function ratedQualitiesCacheKey($user)
    {
        if ($user instanceof User) $user = $user->id;

        return "user_rated_qualities:$user";
    }

    /**
     * Reset user's trust score cache
     *
     * @param User|int
     * @return bool
     */
    public static function resetRatedQualitiesCache($user)
    {
        return Cache::tags(['user_rated_qualities'])
            ->forget(self::ratedQualitiesCacheKey($user));
    }

    /**
     * Reset all of the trust scores from the cache
     *
     * @return bool
     */
    public static function resetAllRatedQualitiesCache()
    {
        return Cache::tags(['user_rated_qualities'])
            ->flush();
    }

    /**
     * Get the top rated qualities of user
     *
     * @return array
     */
    public function getTopRatedQualitiesAttribute()
    {
        $allQualities = $this->rated_qualities;

        if (!($maxCount = Option::maxMemberQualitiesWithoutAccess())) {
            return $allQualities;
        }

        return array_slice($allQualities, 0, $maxCount);
    }
}