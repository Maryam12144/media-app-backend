<?php

namespace Modules\User\Http\Requests\Settings;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Auth\StrongPassword;
use Modules\User\Rules\ValidOldPassword;
use Illuminate\Validation\Rule;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var User $user */
        $user = $this->user();

        return [
            'first_name' => [
                'bail', 'nullable', 'string',
                'min:1', 'max:20',
            ],
            'last_name' => [
                'bail', 'nullable', 'string',
                'min:1', 'max:20'
            ],
            'email' => [
                'bail', 'nullable', 'string', 'email',
                Rule::unique('users')->ignore($user->id)
                // 'email', 'unique:users,email'
            ],
            'phone_number' => [
                'bail', 'nullable', 'numeric', 
                Rule::unique('users')->ignore($user->id)
                // 'unique:users,phone_number'
            ],
            'country' => [
                'bail', 'nullable', 'string',
                'max:15'
            ],
            'state' => [
                'bail', 'nullable', 'string',
                'max:15'
            ],
            'city' => [
                'bail', 'nullable', 'string',
                'max:15'
            ],
            'birthday' => [
                'bail', 'nullable', 'date_format:Y-m-d'
            ],
            'avatar' => [
                // 'bail', 'nullable', 'string',
                // 'base64_image',
                'bail', 'nullable',
                'mimes:jpeg,png,jpg,gif,svg', 'max:2048'
            ],

            'password' => [
                'bail', 'nullable', 'string', 'min:8',
                new StrongPassword
            ],
            'old_password' => [
                'bail', 'required_with:password', 'string',
                new ValidOldPassword($user)
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}