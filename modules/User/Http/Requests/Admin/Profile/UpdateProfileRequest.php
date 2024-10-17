<?php

namespace Modules\User\Http\Requests\Admin\Profile;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Auth\StrongPassword;
use Modules\User\Rules\ValidOldPassword;

class UpdateProfileRequest extends FormRequest
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
                'min:2', 'max:20',
            ],
            'last_name' => [
                'bail', 'nullable', 'string',
                'min:2', 'max:20',
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
        /*
         * Always authorize, because user wants to update
         * their own profile details, so everybody is allowed
         * to do so
         */
        return true;
    }
}
