<?php

namespace Modules\User\Http\Controllers\Reset;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Reset\RequestResetRequest;
use Throwable;

class RequestResetController extends Controller
{
    /**
     * Request for phone number reset
     *
     * @param RequestResetRequest $request
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function request(RequestResetRequest $request)
    {
        $newPhoneNumber = $this->extractPhoneNumber($request);
        $oldPhoneNumber = $this->extractOldPhoneNumber($request);

        if (!($user = $this->fetchUser($oldPhoneNumber))) {
            return $this->returnInvalidOldPhoneNumberResponse();
        }

        if (!$this->fetchPin($newPhoneNumber, $request)) {
            return $this->returnPinNotValidResponse();
        }

        if ($this->duplicatePhoneNumber($newPhoneNumber)) {
            return $this->returnDuplicatePhoneNumberResponse();
        }

        $this->processRequest($user, $newPhoneNumber);

        return $this->successResponse();
    }

    /**
     * Process the reset request for the user
     * and send email to him to complete the process
     *
     * @param User|null $user
     * @param string $newPhoneNumber
     * @throws Throwable
     */
    protected function processRequest($user, $newPhoneNumber)
    {
        DB::transaction(function () use ($user, $newPhoneNumber) {
            $user->sendResetPhoneNotification($newPhoneNumber);
        });
    }

    /**
     * Extract the full phone number from the request
     *
     * @param RequestResetRequest $request
     * @return string
     */
    protected function extractPhoneNumber($request)
    {
        return "+{$request->get('country_code')}{$request->get('phone_number')}";
    }

    /**
     * Extract the full old phone number from the request
     *
     * @param RequestResetRequest $request
     * @return string
     */
    protected function extractOldPhoneNumber($request)
    {
        return "+{$request->get('old_country_code')}{$request->get('old_phone_number')}";
    }

    /**
     * Verify pin for the given phone number
     *
     * @param string $phoneNumber
     * @param RequestResetRequest $request
     */
    protected function fetchPin($phoneNumber, $request)
    {
        return LoginPin::query()
            ->phoneNumber($phoneNumber)
            ->valid()
            ->where('pin', $request->get('pin'))
            ->first();
    }

    /**
     * Fetch the user from the database with the given
     * old phone number
     *
     * @param string $oldPhoneNumber
     * @return User|null
     */
    protected function fetchUser($oldPhoneNumber)
    {
        return User::wherePhoneNumber($oldPhoneNumber)
            ->first();
    }

    /**
     * Check if the provided phone number is duplicate
     *
     * @param string $phoneNumber
     * @return bool
     */
    public function duplicatePhoneNumber($phoneNumber)
    {
        return User::wherePhoneNumber($phoneNumber)
            ->exists();
    }

    /**
     * Return an error response to notify user that
     * the entered old phone number is invalid
     *
     * @return ResponseFactory|Response
     */
    protected function returnInvalidOldPhoneNumberResponse()
    {
        return $this->errorResponse(
            __('user.auth.invalid-old-phone')
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

    /**
     * Return an error response to notify user that
     * the the entered phone number is duplicate
     *
     * @return ResponseFactory|Response
     */
    protected function returnDuplicatePhoneNumberResponse()
    {
        return $this->errorResponse(
            __('user.auth.already-registered')
        );
    }
}
