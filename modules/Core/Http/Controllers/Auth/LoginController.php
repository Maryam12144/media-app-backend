<?php

namespace Modules\Core\Http\Controllers\Auth;

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
use Illuminate\Support\Str;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\LoginRequest;
use Modules\Core\Http\Resources\AuthenticationResource;
use Modules\Core\Libraries\RequestHelpers;
use Modules\Core\Support\Traits\ApiResponses;
use Modules\User\Entities\LoginHistory;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Core\Http\Resources\SelfUserResource;

/**
 * Class LoginWithPinController
 *
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 */
class LoginController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Login with Pin Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */
    use AuthenticatesUsers, ApiResponses;

    /**
     * @param LoginRequest $request
     * @return ResponseFactory|JsonResponse|Response|AuthenticationResource
     */
    public function login(LoginRequest $request)
    {

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }


        $user = User::query()
            ->where('email', $request->get('email'))
            ->first();

        // Send the response after the user was authenticated.
        if ($user && Hash::check($request->get('password'), $user->password)
            && !$user->isAdmin() && !$user->isNormalAdmin()) {
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

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if (!$lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        /*
         * Return error response if login request is invalid
         */
        return response()->json([
            'message' => __('auth.failed')
        ], 401);
    }

    /**
     * Extract the full phone number from the request
     *
     * @param LoginRequest $request
     * @return string
     */
    protected function extractFullPhoneNumber($request)
    {
        return "+{$request->get('country_code')}{$request->get('phone_number')}";
    }

    /**
     * Fetch login record based on the given data
     *
     * @param string $fullPhoneNumber
     * @param LoginRequest $request
     * @return LoginPin|null
     */
    protected function fetchLoginPin($fullPhoneNumber, $request)
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
     * Send an error response to user stating that
     * he has exceeded tries limit
     *
     * @param LoginRequest $request
     * @param string $fullPhoneNumber
     * @return JsonResponse
     */
    protected function sendLockoutResponse($request, $fullPhoneNumber)
    {
        $this->fireLockoutEvent($request);

        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request, $fullPhoneNumber)
        );

        $message = __('auth.throttle', ['seconds' => $seconds]);

        return $this->respondBadRequest($message);
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param Request $request
     * @param string $fullPhoneNumber
     * @return void
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $fullPhoneNumber
     * @return void
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit(
            $this->throttleKey($request),
            $this->decayMinutes() * 60
        );
    }

    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param Request $request
     * @param string $fullPhoneNumber
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request),
            $this->maxAttempts()
        );
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param Request $request
     * @param string $fullPhoneNumber
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->ip());
    }

    /**
     * Return an error response stating that
     * the provided login pin is invalid
     *
     * @return ResponseFactory|Response
     */
    protected function returnInvalidLoginPinResponse()
    {
        return $this->errorResponse(
            __('user.auth.invalid-pin'),
            null, 401
        );
    }

    /**
     * Return an error response stating that
     * the provided phone number has not yet registered
     *
     * @return ResponseFactory|Response
     */
    protected function userNotRegisteredResponse()
    {
        return $this->errorResponse(
            __('user.auth.not-registered')
        );
    }
}