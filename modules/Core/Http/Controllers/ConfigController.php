<?php
/**
 * Created by PhpStorm.
 * User: badiifaoui
 * Date: 8/22/18
 * Time: 1:44 PM
 */

namespace Modules\Core\Http\Controllers;


/**
 * Class ConfigController
 * @package Labs\Core\Http\Controllers
 */
class ConfigController
{
    /**
     * @return array
     */
    public function index()
    {
        return [
            'app.name' => config('app.name'),
            'admin.theme.warning' => config('admin.theme.warning'),
            'admin.theme.success' => config('admin.theme.success'),
            'admin.theme.info' => config('admin.theme.info'),
            'admin.theme.error' => config('admin.theme.error'),
            'admin.theme.accent' => config('admin.theme.accent'),
            'admin.theme.secondary' => config('admin.theme.secondary'),
            'admin.theme.primary' => config('admin.theme.primary'),
        ];
    }
}