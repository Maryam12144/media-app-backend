<?php

namespace Modules\Core\Libraries;

use App\User;

class Option
{
    /**
     * Get the terms and conditions
     *
     * @return string|null
     */
    public static function termsAndConditions()
    {
        return config('options.terms_and_conditions');
    }

    /**
     * Get the privacy policy
     *
     * @return string|null
     */
    public static function privacyPolicy()
    {
        return config('options.privacy_policy');
    }

    /**
     * Get the ios app link
     *
     * @return string|null
     */
    public static function iosAppLink()
    {
        return config('options.ios_app_link');
    }

    /**
     * Get the android app link
     *
     * @return string|null
     */
    public static function androidAppLink()
    {
        return config('options.android_app_link');
    }

    /**
     * Get the message for sharing the app
     *
     * @return string|null
     */
    public static function shareAppMessage()
    {
        $original = config('options.share_app_message');

        $replacedAndroid = str_replace('$android$',
            self::androidAppLink(), $original);

        $replacedIos = str_replace('$ios$',
            self::iosAppLink(), $replacedAndroid);

        return $replacedIos;
    }

    /**
     * Get the minimum number of ratings submitted
     * to let the trust score appear on user's screen
     *
     * @return int|null
     */
    public static function minimumRatingToDisplayTrustScore()
    {
        // return empty if the option is disabled
        if (!self::minimumRatingToDisplayTrustScoreEnabled()) {
            return null;
        }

        return config('options.minimum_ratings_to_display_score');
    }

    /**
     * Check if minimum number of ratings submitted
     * to let the trust score appear on user's screen is enabled
     *
     * @return bool
     */
    public static function minimumRatingToDisplayTrustScoreEnabled()
    {
        return (bool)config('options.minimum_ratings_to_display_score_enabled');
    }

    /**
     * Get the daily number of ratings submitted by each user
     * limit in non-contract type
     *
     * @return int|null
     */
    public static function dailyNonContractRatingLimit()
    {
        // return empty if the option is disabled
        if (!self::dailyNonContractRatingLimitEnabled()) {
            return null;
        }

        return config('options.daily_non_contract_rating_limit');
    }

    /**
     * Check if the daily number of ratings submitted by each user
     * limit in non-contract type is enabled
     *
     * @return bool
     */
    public static function dailyNonContractRatingLimitEnabled()
    {
        return (bool)config('options.daily_non_contract_rating_limit_enabled');
    }

    /**
     * Get the number of cool-down days before
     * rating the same user again
     *
     * @return int|null
     */
    public static function daysBeforeRatingSameUserAgain()
    {
        // return empty if the option is disabled
        if (!self::daysBeforeRatingSameUserAgainEnabled()) {
            return null;
        }

        return config('options.days_before_rating_same_user_again');
    }

    /**
     * Check if the number of cool-down days before
     * rating the same user again is enabled
     *
     * @return bool
     */
    public static function daysBeforeRatingSameUserAgainEnabled()
    {
        return (bool)config('options.days_before_rating_same_user_again_enabled');
    }

    /**
     * Get the hours given to the both parties of
     * a contract to rate each other before the rating time
     * is over
     *
     * @return int|null
     */
    public static function contractRatingWaitingHours()
    {
        // return empty if the option is disabled
        if (!self::contractRatingWaitingHoursEnabled()) {
            return null;
        }

        return config('options.contract_rating_waiting_hours');
    }

    /**
     * Check if the hours given to the both parties of
     * a contract to rate each other before the rating time
     * is over is enabled
     *
     * @return bool
     */
    public static function contractRatingWaitingHoursEnabled()
    {
        return (bool)config('options.contract_rating_waiting_hours_enabled');
    }

    /**
     * Get the max member qualities count if no
     * access given to the visiting member
     *
     * @return int|null
     */
    public static function maxMemberQualitiesWithoutAccess()
    {
        // return empty if the option is disabled
        if (!self::maxMemberQualitiesWithoutAccessEnabled()) {
            return null;
        }

        return config('options.max_member_qualities_without_access');
    }

    /**
     * Check if the max member qualities count if no
     * access given to the visiting member is enabled
     *
     * @return bool
     */
    public static function maxMemberQualitiesWithoutAccessEnabled()
    {
        return (bool)config('options.max_member_qualities_without_access_enabled');
    }

    /**
     * Get the global caching time for the application
     *
     * @return int|null
     */
    public static function globalCacheExpirationTime()
    {
        // return empty if the option is disabled
        if (!self::globalCacheExpirationTimeEnabled()) {
            return null;
        }

        return config('options.global_cache_expiration_time');
    }

    /**
     * Check if the global caching time for the application is enabled
     *
     * @return bool
     */
    public static function globalCacheExpirationTimeEnabled()
    {
        return (bool)config('options.global_cache_expiration_time_enabled');
    }

    /**
     * Get the default starting trust score for the
     * newly registered users
     *
     * @return int|null
     */
    public static function defaultStartingTrustScore()
    {
        // return empty if the option is disabled
        if (!self::defaultStartingTrustScoreEnabled()) {
            return User::DEFAULT_TRUST_SCORE;
        }

        return config('options.default_starting_trust_score');
    }

    /**
     * Check if the default starting trust score for the
     * newly registered users is enabled
     *
     * @return bool
     */
    public static function defaultStartingTrustScoreEnabled()
    {
        return (bool)config('options.default_starting_trust_score_enabled');
    }
}