<?php

namespace Modules\Core\Http\Controllers\Auth\Admin;

use App\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\AdminLoginRequest;
use Modules\Core\Http\Resources\SelfUserResource;
use Modules\Core\Libraries\RequestHelpers;
use Modules\Core\Support\Traits\ApiResponses;
use Modules\User\Entities\LoginHistory;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Class LoginController
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 *
 */
class AdminLoginController extends Controller
{
    /*
   |--------------------------------------------------------------------------
   | Login Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles authenticating users for the application and
   | redirecting them to your home screen. The controller uses a trait
   | to conveniently provide its functionality to your applications.
   |
   */

    use AuthenticatesUsers, ApiResponses;

    /**
     * @OA\Post (
     *     path="/api/login",
     *     tags={"Auth"},
     *     description="Login User",
     *     operationId="login",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/AdminLoginRequest" )
     *     ),
     *     @OA\Response(response=200, description="",
     *        @OA\JsonContent(ref="#/components/schemas/AuthResponse" )
     *     ),
     *     @OA\Response(
     *          response=401, description="",
     *          @OA\JsonContent(ref="#/components/schemas/FailedResponse" )
     *     ),
     * ),
     * @param AdminLoginRequest $request
     * @return Application|ResponseFactory|JsonResponse|Response
     */
    public function login(AdminLoginRequest $request)
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $user = User::query()
            ->where('email', $request->get('email'))
            ->first();
        // Send the response after the user was authenticated.
        if($user): 
            if (Hash::check($request->get('password'), $user->password)
                && $user->isAdmin()) {
                $this->clearLoginAttempts($request);

                /*
                 * Clear login attempts, create a new session
                 * and generate a new token for user
                 */
                $token = $this->finalizeLogin($request, $user);

                return response([
                    'token' => $token,
                    'user' => new SelfUserResource($user)
                ]);
            }
        endif;

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return response()->json([
            'message' => __('auth.failed')
        ], 401);
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
        $this->clearLoginAttempts($request);

        $token = $user->createToken(
            $request->server('HTTP_USER_AGENT')
        );

        // $this->createLoginHistory($user,
            // $request);

        return $token->plainTextToken;
    }

    /**
     * Create a new login history for the
     * logged in user as an admin
     *
     * @param User $user
     * @param Request $request
     * @return Model|LoginHistory
     */
    protected function createLoginHistory($user, $request)
    {
        return LoginHistory::createAdminLoginHistory(
            $user,
            RequestHelpers::device(),
            $request->ip()
        );
    }

    /**
     * Clear the current token for user access
     * and logout
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function logout()
    {
        /** @var PersonalAccessToken $currentToken */
        $currentToken = Auth::user()->currentAccessToken();

        // delete the current token
        $currentToken->delete();

        return $this->successResponse();
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    protected function sendLockoutResponse($request)
    {
        $this->fireLockoutEvent($request);

        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = __('auth.throttle', ['seconds' => $seconds]);

        return $this->respondBadRequest($message);
    }
}