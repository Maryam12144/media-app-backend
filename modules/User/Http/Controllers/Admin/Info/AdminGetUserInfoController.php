<?php

namespace Modules\User\Http\Controllers\Admin\Info;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Resources\SelfUserResource;

/**
 * Class GetUserInfoController
 */
class AdminGetUserInfoController extends Controller
{
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
     * @return Application|ResponseFactory|Response
     */
    public function info()
    {
        $user = Auth::user();

        return response((new SelfUserResource($user))
            ->toArray(request()));
    }

}
