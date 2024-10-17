<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Events\UserRegistered;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\RegisterRequest;
use Modules\Core\Http\Resources\AuthenticationResource;
use Modules\Core\Libraries\RequestHelpers;
use Modules\User\Entities\LoginHistory;
use Modules\Core\Entities\Role;
use Modules\User\Entities\BlacklistedPhoneNumber;
use Throwable;

/**
 * Class RegisterController
 *
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 *
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Register a user on the website
     *
     * @param RegisterRequest $request
     * @return
     * @throws Throwable
     */
    public function register(RegisterRequest $request)
    {
        $fullPhoneNumber = $this->extractFullPhoneNumber($request);

        /*
         * Check if the a valid login pin record exists
         * for the provided phone number
         * Return error if the given pin is invalid or expired
         */
        /*
         * Return error if another user has already registered with the
         * provided phone number
         */
        if ($this->phoneNumberTaken($fullPhoneNumber)) return $this->returnAlreadyRegisteredResponse();

        /*
         * If everything is okay, just create a new user with
         * the user input and generate a login token for them
         */ 
        $user = $this->createUser($request, $fullPhoneNumber)
                ->assignRole(Role::ROLE_USER);
        $token = $this->finalizeLogin($request, $user);

        event(new UserRegistered($user));

        return new AuthenticationResource($user, $token);
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
     * Extract the full phone number from the request
     *
     * @param RegisterRequest $request
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
     * @param RegisterRequest $request
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
     * Return an error response stating that the phone
     * number has already registered
     *
     * @return ResponseFactory|Response
     */
    protected function returnAlreadyRegisteredResponse()
    {
        return $this->errorResponse(
            __('user.auth.already-registered')
        );
    }

    /**
     * Create a new company owner user
     *
     * @param RegisterRequest $request
     * @param string $fullPhoneNumber
     * @return User
     * @throws Throwable
     */
    protected function createUser($request, $fullPhoneNumber)
    {
        DB::transaction(function () use (&$user, $request, $fullPhoneNumber) {
            $user = User::createUser(
                $fullPhoneNumber,
                $request->get('email'),
                $request->get('password'),
                $request->get('first_name'),
                $request->get('last_name'),
            );
        });

        return $user;
    }

    /**
     * Check if the phone number has already been taken
     *
     * @param string $fullPhoneNumber
     * @return bool
     */
    protected function phoneNumberTaken(string $fullPhoneNumber)
    {
        $alreadyTaken = User::wherePhoneNumber($fullPhoneNumber)
            ->exists();

        if ($alreadyTaken) return true;

        // return BlacklistedPhoneNumber::wherePhoneNumber($fullPhoneNumber)
        //     ->exists();
    }
}