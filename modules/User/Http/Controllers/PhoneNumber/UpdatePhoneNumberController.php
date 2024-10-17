<?php

namespace Modules\User\Http\Controllers\PhoneNumber;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User\LoginPin;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Entities\BlacklistedPhoneNumber;
use Modules\User\Http\Requests\PhoneNumber\UpdatePhoneNumberRequest;
use Throwable;

class UpdatePhoneNumberController extends Controller
{
    /**
     * Update user phone number
     *
     * @param UpdatePhoneNumberRequest $request
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function update(UpdatePhoneNumberRequest $request)
    {
        $user = Auth::user();

        $fullPhoneNumber = $this->extractFullPhoneNumber($request);

        if (!$this->fetchPin($fullPhoneNumber, $request)) {
            return $this->returnPinNotValidResponse();
        }

        if ($this->duplicatePhoneNumber($fullPhoneNumber)) {
            return $this->returnDuplicatePhoneNumberResponse();
        }

        $this->processPhoneChange($user,
            $fullPhoneNumber);

        return $this->successResponse(
            __('user.phone.updated')
        );
    }

    /**
     * Process the phone number change for user
     *
     * @param User $user
     * @param string $fullPhoneNumber
     * @return void
     * @throws Throwable
     */
    protected function processPhoneChange($user, $fullPhoneNumber)
    {
        DB::transaction(function () use ($user, $fullPhoneNumber) {
            BlacklistedPhoneNumber::createRecord($user,
                $user->phone_number);

            $user->update([
                'phone_number' => $fullPhoneNumber
            ]);
        });
    }

    /**
     * Extract the full phone number from the request
     *
     * @param UpdatePhoneNumberRequest $request
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
     * @param UpdatePhoneNumberRequest $request
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
     * Check if the provided phone number is duplicate
     *
     * @param string $fullPhoneNumber
     * @return bool
     */
    public function duplicatePhoneNumber($fullPhoneNumber)
    {
        return User::wherePhoneNumber($fullPhoneNumber)
            ->exists();
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
