<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Entities\User\PasswordReset;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\User\ResetPasswordRequest;
use Modules\Core\Support\Traits\ApiResponses;

/**
 * Class MemberResetPasswordController
 *
 * @package Labs\Core\Http\Controllers\Auth
 * @resource Authentication
 *
 */
class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, ApiResponses;

    /**
     * Finalize the user password reset
     * based on the provided details
     *
     * @OA\Post (
     *     path="/api/forgot-password/reset",
     *     tags={"Auth"},
     *     description="Password Reset",
     *     operationId="passwordReset",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/ResetPasswordRequest" )
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
     *
     * @param ResetPasswordRequest $request
     * @return Application|ResponseFactory|Response
     * @throws Exception
     */
    public function reset(ResetPasswordRequest $request)
    {
        $record = $this->getPasswordReset(
            $email = $request->get('email'),
            $pin = $request->get('pin')
        );

        /*
         * Reset user's password to the new one and
         * then delete the password reset record from
         * the database
         */
        $this->resetPassword(
            $email, $request->get('password')
        );
        $record->delete();

        return $this->successResponse();
    }

    /**
     * Verify the provided reset password pin
     *
     * @OA\Schema(
     *      type="object",
     *      schema="VerifyResetPasswordRequest",
     *      required={"pin", "email"},
     *      @OA\Property(property="pin", type="number", minLength=4, maxLength=4,),
     *      @OA\Property(property="email", type="string", format="email", minLength=3, maxLength=255),
     * )
     *
     * @OA\Post (
     *     path="/api/forgot-password/verify",
     *     tags={"Auth"},
     *     description="Password Verify Pin",
     *     operationId="passwordVerifyPin",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/VerifyResetPasswordRequest" )
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
     *
     * @param Request $request
     * @return Application|ResponseFactory|ModelNotFoundException|Response
     * @throws Exception
     */
    public function verify(Request $request)
    {
        $record = $this->getPasswordReset(
            $request->get('email'),
            $request->get('pin')
        );

        /*
         * First extend it for a few minutes then return
         * a success response to the user
         */
        $record->extend();

        return $this->successResponse();
    }

    /**
     * Fetch password reset record by
     * the given email and pin
     *
     * @param string $email
     * @param string|int $pin
     * @return Model|HasMany|PasswordReset
     * @throws Exception
     */
    protected function getPasswordReset($email, $pin)
    {
        /** @var PasswordReset $record */
        $record = User::whereEmail($email)
            ->firstOrFail()
            ->passwordResets()
            ->where('pin', $pin)
            ->firstOrFail();

        /*
         * Delete the password reset record and return
         * Not-Found error if it has already expired
         */
        if ($record->hasExpired()) {
            $record->delete();

            throw new ModelNotFoundException();
        }

        return $record;
    }

    /**
     * Reset the given user's password.
     *
     * @param string $email
     * @param string $password
     * @return bool|int
     */
    protected function resetPassword($email, $password)
    {
        return User::where('email', $email)
            ->update([
                'password' => Hash::make($password)
            ]);
    }
}
