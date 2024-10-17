<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Modules\Core\Libraries\Option;

class OptionsController extends Controller
{
    /**
     * Get the terms and conditions
     *
     * @return ResponseFactory|Response
     */
    public function termsAndConditions()
    {
        return $this->dataResponse([
            'terms_and_conditions' => Option::termsAndConditions()
        ]);
    }

    /**
     * Get the privacy policy
     *
     * @return ResponseFactory|Response
     */
    public function privacyPolicy()
    {
        return $this->dataResponse([
            'privacy_policy' => Option::privacyPolicy()
        ]);
    }

    /**
     * Get the minimum ratings count to display trust score
     *
     * @return ResponseFactory|Response
     */
    public function minRatingsForTrustScore()
    {
        return $this->dataResponse([
            'min_ratings_for_trust_score' => Option::minimumRatingToDisplayTrustScore() ?: null
        ]);
    }

    /**
     * Get the ios and android app links
     *
     * @return ResponseFactory|Response
     */
    public function appLinks()
    {
        return $this->dataResponse([
            'share_app_message' => Option::shareAppMessage() ?: null,
            'ios_app_link' => Option::iosAppLink() ?: null,
            'android_app_link' => Option::androidAppLink() ?: null
        ]);
    }
}
