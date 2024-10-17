<?php

namespace Modules\Core\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Auth\StrongPassword;

/**
 * Class ResetPasswordRequest
 * @package Labs\Core\Http\Requests\Participant
 * 
 * @OA\Schema(
 *      type="object",
 *      schema="ResetPasswordRequest",
 *      required={"code"},
 *      @OA\Property(property="pin", type="number", minLength=4, maxLength=4,),
 *      @OA\Property(property="email", type="string", format="email", minLength=3, maxLength=255),
 *      @OA\Property(property="password", type="string", minLength=8, maxLength=60),
 *      @OA\Property(property="password_confirmation", type="string", minLength=8, maxLength=60),
 * )
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pin' => [
                'required', 'numeric', 'digits:4'
            ],
            'email' => [
                'required', 'email', 'exists:users,email'
            ],
            'password' => [
                'bail', 'required', 'string', 'min:8',
                new StrongPassword
            ],
        ];
    }
}