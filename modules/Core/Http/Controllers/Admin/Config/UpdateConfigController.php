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
 *
 * @package Labs\Core\\Http\Controllers/Admin
 */
class UpdateConfigController extends Controller
{
    /**
     * Update config based on user input
     *
     * @param ConfigRequests\UpdateConfigRequest $request
     * @param Config $config
     * @return ResponseFactory|Response
     */
    public function update(ConfigRequests\UpdateConfigRequest $request, Config $config)
    {
        $this->updateConfig($config, $request);

        Config::resetCache();

        return $this->successResponse(
            __('admin.rules.updated'), [
            'config' => new ConfigResource($config)
        ]);
    }

    /**
     * Apply the requested changes on config
     *
     * @param Config $config
     * @param ConfigRequests\UpdateConfigRequest $request
     * @return bool
     */
    protected function updateConfig($config, $request)
    {
        return $config->update([
            'value' => $request->get('value')
        ]);
    }
}
