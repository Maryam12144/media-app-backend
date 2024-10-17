<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\User\EmailVerification;
use Modules\Core\Events\UserVerifiedEmail;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\VerifyEmailRequest;

/**
 * Class VerifyEmailController
 *
 * @package Zix\PluginName\Http\Controllers
 */
class VerifyEmailController extends Controller
{
    /**
     * Resend the verification email to user
     *
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function resend()
    {
        $user = Auth::user();

        $user->sendEmailVerificationNotification();

        return $this->successResponse();
    }

    /**
     * Verify user email using verification code
     *
     * @param VerifyEmailRequest $request
     * @return ResponseFactory|Response
     */
    public function verify(VerifyEmailRequest $request)
    {
        /*
         * Extract the verification ID and token
         * from the given code
         */
        list($id, $token) = $this->explodeVerificationCode(
            $request->get('code')
        );

        if (
            // if the exploded code doesn't contain ID
            !$id
            // or if the exploded code doesn't contain token
            or !$token
            // or if the verification record doesn't exist or has expired
            or !($verification = $this->fetchVerificationRecord($id, $token))
        ) {
            /*
             * Then return an error stating that
             * the verification code is invalid
             */
            return $this->returnInvalidCodeResponse();
        }

        $this->finalizeVerification($verification->user);

        return $this->successResponse(
            __('messages.verify-email.verified'), [
                'token' => $verification->user->generateLoginToken($request)
            ]
        );
    }

    /**
     * Explode email verification into ID and token
     *
     * @param string $code
     * @return null
     */
    protected function explodeVerificationCode($code)
    {
        $exploded = explode('|', $code);

        $invalidCode = (count($exploded) != 2
            or !is_numeric($exploded[0])
            or (strlen($exploded[1]) != 32));

        return $invalidCode ? null : ([
            $exploded[0], $exploded[1]
        ]);
    }

    /**
     * Fetch email verification record from the database
     * using the given ID and token
     *
     * @param int $id
     * @param string $token
     * @return EmailVerification|null
     */
    protected function fetchVerificationRecord($id, $token)
    {
        $verification = EmailVerification::fetch($id, $token);

        $invalidVerification = !$verification
            || $verification->hasExpired();

        return $invalidVerification
            ? null : $verification;
    }

    /**
     * Finalize the user email verification process
     *
     * @param User $user
     * @return mixed
     */
    protected function finalizeVerification(User $user)
    {
        $user->markEmailAsVerified();

        // trigger a new event for email verification
        event(new UserVerifiedEmail($user));

        return $this->clearVerificationRecords($user);
    }

    /**
     * Clear email verification records
     * created for user
     *
     * @param User $user
     * @return mixed
     */
    protected function clearVerificationRecords(User $user)
    {
        return $user->emailVerifications()->delete();
    }

    /**
     * Return invalid code response to user
     *
     * @return ResponseFactory|Response
     */
    protected function returnInvalidCodeResponse()
    {
        return $this->errorResponse(
            __('validation.email-verification.invalid-code')
        );
    }
}
