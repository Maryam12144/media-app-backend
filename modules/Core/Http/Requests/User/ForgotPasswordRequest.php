<?php

namespace Modules\Core\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ForgotPasswordRequest
 * @package Labs\Core\Http\Requests\Participant
 * @OA\Schema(
 *      type="object",
 *      schema="ForgotPasswordRequest",
 *      required={"email"},
 *      @OA\Property(property="email", type="string", format="email", minLength=3, maxLength=255),
 * )
 */
class ForgotPasswordRequest extends FormRequest
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
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }
}