<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\VerifyLoginPinRequest;
use Modules\Core\Http\Resources\AuthenticationResource;
use Modules\Core\Libraries\RequestHelpers;
use Modules\User\Entities\LoginHistory;

/**
 * Class VerifyLoginPinController
 *
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 */
class VerifyLoginPinController extends Controller
{
    /**
     * @param VerifyLoginPinRequest $request
     * @return Response|ResponseFactory|AuthenticationResource
     * @throws Exception
     */

    public function verify(VerifyLoginPinRequest $request)
    {
        $fullPhoneNumber = $this->extractFullPhoneNumber($request);

        $loginPin = $this->fetchPin($fullPhoneNumber, $request);

        if (!$loginPin) {
            return $this->returnPinNotValidResponse();
        }

        $loginPin->extend();
        $user = $loginPin->user;

        /*
         * If user has already registered, just return login response
         */
        if ($user) {
            $token = $this->finalizeLogin($request, $user);

            return new AuthenticationResource($user, $token);
        }

        return $this->successResponse(
            null, [
                'user' => null
            ]
        );
    }

    /**
     * Extract the full phone number from the request
     *
     * @param VerifyLoginPinRequest $request
     * @return string
     */
    protected function extractFullPhoneNumber($request)
    {
        return "+{$request->get('country_code')}{$request->get('phone_number')}";
    }

    /**
     * Verify pin for the given phone number
     *
     * @param string $fullPhoneNumber
     * @param VerifyLoginPinRequest $request
     */
    protected function fetchPin($fullPhoneNumber, $request)
    {
        return LoginPin::query()
            ->phoneNumber($fullPhoneNumber)
            ->valid()
            ->where('pin', $request->get('pin'))
            ->first();
    }

    /**
     * Finalize the login process
     *
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    protected function finalizeLogin(Request $request, User $user)
    {
        $token = $user->createToken(
            $request->server('HTTP_USER_AGENT')
        );

        $this->createLoginHistory($user,
            $request);

        return $token->plainTextToken;
    }

    /**
     * Create a new login history for the
     * logged in user
     *
     * @param User $user
     * @param Request $request
     * @return Model|LoginHistory
     */
    protected function createLoginHistory($user, $request)
    {
        return LoginHistory::createNormalLoginHistory(
            $user,
            RequestHelpers::device(),
            $request->ip()
        );
    }

    /**
     * Return an error response to notify user that
     * the pin is invalid
     *
     * @return ResponseFactory|Response
     */
    protected function returnPinNotValidResponse()
    {
        return $this->errorResponse(
            __('user.auth.invalid-pin')
        );
    }

}