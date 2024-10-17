<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Response;
use Modules\Core\Entities\Role;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\ForgotPasswordRequest;
use Modules\Core\Support\Traits\ApiResponses;

/**
 * Class ForgotPasswordController
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 *
 */
class ForgotPasswordController extends Controller
{
    /*
       |--------------------------------------------------------------------------
       | Password Reset Controller
       |--------------------------------------------------------------------------
       |
       | This controller is responsible for handling password reset emails and
       | includes a trait which assists in sending these notifications from
       | your application to your users. Feel free to explore this trait.
       |
       */

    use SendsPasswordResetEmails, ApiResponses;

    /**
     * @OA\Post (
     *     path="/forgot-password",
     *     tags={"Auth"},
     *     description="Password Forgot",
     *     operationId="passwordForgot",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ForgotPasswordRequest" )
     *     ),
     *      @OA\Response(
     *          response=200, description="",
     *          @OA\JsonContent(ref="#/components/schemas/FailedResponse" )
     *     ),
     *     @OA\Response(
     *          response=401, description="",
     *          @OA\JsonContent(ref="#/components/schemas/FailedResponse" )
     *     ),
     * ),
     * @param ForgotPasswordRequest $request
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function request(ForgotPasswordRequest $request)
    {
        /**
         * @var User $user
         */
        $user = User::query()
            ->where('email', $request->get('email'))
            ->role(Role::ROLE_USER)
            ->firstOrFail();

        $user->sendForgotPasswordNotification();

        return $this->successResponse();
    }
}
