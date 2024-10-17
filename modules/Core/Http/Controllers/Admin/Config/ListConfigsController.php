<?php

namespace Modules\Core\Http\Controllers\Admin\Config;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Modules\Core\Entities\Config;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Config as ConfigRequests;
use Modules\Core\Http\Resource\Admin\ConfigResource;

/**
 * Class ConfigController
 * @package Labs\Core\\Http\Controllers/Admin
 */
class ListConfigsController extends Controller
{
    /**
     * List the configs
     *
     * @param ConfigRequests\ListConfigsRequest $request
     * @return ResponseFactory|Response
     */
    public function index(ConfigRequests\ListConfigsRequest $request)
    {
        $configs = Config::query();

        if ($group = $request->get('group')) {
            $configs->group($group);
        }

        return $this->dataResponse([
            'configs' => ConfigResource::collection(
                $configs->get()
            )
        ]);
    }
}
