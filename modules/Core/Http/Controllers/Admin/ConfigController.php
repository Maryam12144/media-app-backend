<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Modules\Core\Entities\Config;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Config as ConfigRequests;
use Modules\Core\Http\Resource\Admin\ConfigResource;

/**
 * Class ConfigController
 * @property Config model
 * @package Labs\Core\\Http\Controllers/Admin
 */
class ConfigController extends Controller
{
    /**
     * ConfigController constructor.
     * @param Config $model
     */
    public function __construct(Config $model)
    {
        $this->model = $model;
    }

    /**
     * @SWG\Get(
     *     path="/admin/configs",
     *     tags={"Admin Core Configs"},
     *     summary="Get Config List",
     *     @SWG\Response(
     *          response=200,
     *          description="ORACLE Fluent Query Response.",
     *      ),
     * ),
     * @param ConfigRequests\ConfigShowRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ConfigRequests\ConfigShowRequest $request)
    {
        return $this->getConfigs($request);
    }

    /**
     * @SWG\Post(
     *     path="/admin/configs",
     *     tags={"Admin Core Configs"},
     *     summary="Create New Config",
     *     @SWG\Parameter(
     *          name="config",
     *          in="body",
     *          required=true,
     *          type="string",
     *          schema={"$ref": "#/definitions/Core@AdminConfigStoreRequest"},
     *        ),
     *     @SWG\Response(
     *          response=200,
     *          description="ConfigResource.",
     *      ),
     * ),
     *
     * @param  ConfigRequests\ConfigStoreRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(ConfigRequests\ConfigStoreRequest $request)
    {
        foreach ($request->get('configs') as $config) {
            $this->model->updateOrCreate(
                ['key' => $config['key'], 'group' => $config['group']],
                ['value' => $config['value']]
            );
        }
        Cache::forget('app_configs');
        return $this->getConfigs($request);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    private function getConfigs(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return ConfigResource::collection($this->model->query()->whereIn('group', explode(',', $request->get('group')))->get());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function images(Request $request)
    {
        if ($request->has('site_logo') && base64_decode($request->get('site_logo'))) {
            Image::make($request->get('site_logo'))->save(public_path('images/logo.png'));
        }

        if ($request->has('site_login_bg') && base64_decode($request->get('site_login_bg'))) {
            Image::make($request->get('site_login_bg'))->save(public_path('images/login-bg.png'));
        }

        if ($request->has('site_fav_icon') && base64_decode($request->get('site_fav_icon'))) {
            Image::make($request->get('site_fav_icon'))->save(public_path('favicon.ico'));
        }

        return response()->json(true);
    }
}
