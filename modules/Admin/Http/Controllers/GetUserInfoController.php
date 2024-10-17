<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Resources\SelfUserResource;

/**
 * Class GetUserInfoController
 * @package Zix\PluginName\Http\Controllers
 */
class GetUserInfoController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['verified-email']);
    }
    /**
     * @OA\Get (
     *      path="/api/info",
     *      tags={"User"},
     *      security={{"bearer_token":{}}},
     *      operationId="getUser",
     *      @OA\Response(response=200, description="",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  ref="#/components/schemas/UserResponse"
     *              ),
     *          ),
     *
     *      ),
     *      @OA\Response(
     *          response=401, description="",
     *          @OA\Property(property="data", type="object"),
     *          @OA\JsonContent(ref="#/components/schemas/FailedResponse" )
     *      ),
     *
     * ),
     * @return SelfUserResource
     */
    public function info()
    {
        $user = Auth::user();

        return new SelfUserResource($user);
    }

}
