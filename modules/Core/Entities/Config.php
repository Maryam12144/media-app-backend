<?php

namespace Modules\Core\Entities;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class Config
 * @package Labs\Core\Entities
 */
class Config extends BaseModel
{
    /**
     * Group names for configs
     */
    const GROUP_OPTIONS = 'options';

    /**
     * @var array
     */
    protected $fillable = [
        'value', 'key', 'group'
    ];

    /**
     * On-boot model hooks
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /*
         * Handle "created" event
         */
        self::created(function (Config $config) {
            self::resetCache();
        });

        /*
         * Handle "updated" event
         */
        self::updated(function (Config $config) {
            self::resetCache();

            if ($config->key == 'max_member_qualities_without_access') {
                User::resetAllRatedQualitiesCache();
            }
        });

        /*
         * Handle "deleted" event
         */
        self::deleted(function (Config $config) {
            self::resetCache();
        });
    }

    /**
     * Reset the cache for the stored configs
     *
     * @return void
     */
    public static function resetCache()
    {
        Cache::forget('app_configs');
        Cache::forget('app_configs:options');
    }

    /**
     * Apply group on configs query
     *
     * @param Builder $query
     * @param string $group
     * @return Builder
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Load the configs from cache
     *
     * @return Collection
     */
    public static function loadConfigs()
    {
        return Cache::rememberForever('app_configs', function () {
            return self::all()->pluck('value', 'key');
        });
    }

    /**
     * Get the option configs from cache
     *
     * @return array
     */
    public static function getOptions()
    {
        return Cache::rememberForever('app_configs:options', function () {
            $options = Config::query()
                ->group(self::GROUP_OPTIONS)
                ->select([
                    'id', 'group',
                    'key', 'value'
                ])
                ->get();

            return $options->toArray();
        });
    }

    /**
     * Get all of the config groups
     *
     * @return array
     */
    public static function allGroups()
    {
        return [
            self::GROUP_OPTIONS,
        ];
    }
}
