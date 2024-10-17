<?php

namespace Modules\Core\Http\Controllers\Auth;

use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Events\Auth\SendLoginPinSmsEvent;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\RequestLoginPinRequest;
use Modules\User\Entities\BlacklistedPhoneNumber;

/**
 * Class RequestLoginPinController
 *
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 */
class RequestLoginPinController extends Controller
{
    /**
     * @param RequestLoginPinRequest $request
     * @return Response|ResponseFactory
     * @throws Exception
     */
    public function request(RequestLoginPinRequest $request)
    {
        $fullPhoneNumber = $this->extractFullPhoneNumber($request);

        /*
         * Check if the given phone number is blacklisted and return
         * an error if it is
         */
        if ($this->phoneNumberBlacklisted($fullPhoneNumber)) {
            return $this->returnPhoneNumberBlacklistedResponse();
        }

        $loginPin = $this->generateLoginPin($fullPhoneNumber);

        event(new SendLoginPinSmsEvent($loginPin));

        return $this->successResponse();
    }

    /**
     * Extract the full phone number from the request
     *
     * @param RequestLoginPinRequest $request
     * @return string
     */
    protected function extractFullPhoneNumber($request)
    {
        return "+{$request->get('country_code')}{$request->get('phone_number')}";
    }

    /**
     * Generate a login pin for the given phone number
     *
     * @param string $phoneNumber
     * @return Model|LoginPin
     */
    protected function generateLoginPin($phoneNumber)
    {
        return LoginPin::generate($phoneNumber);
    }

    /**
     * Check if the phone number is not usable anymore
     *
     * @param string $fullPhoneNumber
     * @return bool
     */
    protected function phoneNumberBlacklisted($fullPhoneNumber)
    {
        return BlacklistedPhoneNumber::wherePhoneNumber($fullPhoneNumber)
            ->exists();
    }

    /**
     * Return an error response to the user stating
     * that the provided phone number has been used
     * sometime before and cannot be used anymore
     *
     * @return ResponseFactory|Response
     */
    protected function returnPhoneNumberBlacklistedResponse()
    {
        return $this->errorResponse(
            __('user.auth.phone-blacklisted')
        );
    }
}