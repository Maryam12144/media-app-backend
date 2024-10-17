<?php

return [

    'models' => [

        'users' => App\User::class,
        'news' => Modules\News\Entities\News::class,
        'video_types' => Modules\News\Entities\videoType::class,
        'genres' => Modules\News\Entities\Genre::class,
        'geographical_criterias' => Modules\News\Entities\GeographicalCriteria::class,
        'prominences' => Modules\News\Entities\Prominence::class,
        'human_interests' => Modules\News\Entities\HumanInterest::class,
        'news_types' => Modules\News\Entities\NewsType::class,
        'report_types' => Modules\News\Entities\ReportType::class,
        'package_types' => Modules\News\Entities\PackageType::class,
        'news_has_proximity' => Modules\News\Entities\NewsHasProximity::class,
        'news_has_report' => Modules\News\Entities\NewsHasReport::class,
        'news_has_package' => Modules\News\Entities\NewsHasPackage::class,

    ],

    'table_names' => [

        'users' => 'users',
        'news' => 'news',
        'video_types' => 'video_types',
        'genres' => 'genres',
        'geographical_criterias' => 'geographical_criterias',
        'prominences' => 'prominences',
        'human_interests' => 'human_interests',
        'news_types' => 'news_types',
        'report_types' => 'report_types',
        'package_types' => 'package_types',

        'news_has_proximities' => 'news_has_proximities',
        'news_has_reports' => 'news_has_reports',
        'news_has_packages' => 'news_has_packages',

    ],

    /*
     * By default all tables will be cached for 24 hours unless a table
     * is updated. Then the cache will be flushed immediately.
     */

    'cache_expiration_time' => 60 * 24,
];
