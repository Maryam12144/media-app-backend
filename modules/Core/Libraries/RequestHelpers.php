<?php

namespace Modules\Core\Libraries;

use Browser;
use Illuminate\Http\Request;

class RequestHelpers
{
    /**
     * Traverse through the request keys and only return those
     * which don't have a value of NULL
     *
     * @param Request $request
     * @param array $normalKeys
     * @param array $notNullableKeys
     * @return array
     */
    public static function filterKeys($request, $normalKeys, $notNullableKeys)
    {
        $filteredNotNullableKeys = [];

        /*
         * Traverse through each key which can not be
         * passed with the value of NULL
         */
        foreach ($notNullableKeys as $key) {
            /*
             * If request contains a key
             */
            if ($request->exists($key) && !is_null($request->get($key))) {
                $filteredNotNullableKeys[] = $key;
            }
        }

        return array_merge($filteredNotNullableKeys, $normalKeys);
    }

    /**
     * Get full device name of the current request
     *
     * @return string
     */
    public static function device()
    {
        $deviceInfoSlices = [];

        if ($platform = Browser::platformName()) {
            $deviceInfoSlices[] = $platform;
        }

        if ($browser = Browser::browserName()) {
            $deviceInfoSlices[] = $browser;
        }

        $deviceInfoSlices[] = trim(Browser::deviceFamily() . ' ' . Browser::deviceModel())
            ?: null;

        return implode(', ',
            $deviceInfoSlices);
    }
}