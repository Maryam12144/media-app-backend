<?php

namespace Modules\Core\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Entities\User\LoginPin;

/**
 * Class RegisterRequest
 * @package Labs\Core\Http\Requests\User
 * @SWG\Definition(
 *     definition="Core@RegisterRequest",
 *     required={"email", "password", "password_confirmation"},
 *     @SWG\Property(
 *          property="email",
 *          type="email",
 *          description="Email",
 *          example="example@example.com"
 *    ),
 *    @SWG\Property(
 *          property="password",
 *          type="password",
 *          description="Password",
 *          example="123456789"
 *    ),
 *     @SWG\Property(
 *          property="password_confirmation",
 *          type="password",
 *          description="Password Confirmation",
 *          example="123456789"
 *    ),
 * ),
 */
class RegisterRequest extends FormRequest
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
            'first_name' => [
                'required', 'string', 'max:20'
            ],
            'last_name' => [
                'required', 'string', 'max:20'
            ],

            'email' => [
                'required', 'email', 'unique:users,email',
                'max:100'
            ],

            'password' => [
                // 'required', 'password', 'max:100'
                'required', 'min:8', 'max:100'
            ],

            'phone_number' => [
                'required', 'numeric', 'digits_between:3,18'
            ],
            
        ];
    }
}