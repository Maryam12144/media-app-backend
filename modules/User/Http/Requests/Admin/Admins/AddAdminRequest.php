<?php

namespace Modules\User\Http\Requests\Admin\Admins;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\Auth\StrongPassword;

class AddAdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => [
                'bail', 'nullable', 'string', 'min:2',
                'max:20',
            ],
            'last_name' => [
                'bail', 'nullable', 'string', 'min:2',
                'max:20',
            ],
            'email' => [
                'required', 'email', 'unique:users,email',
                'max:120'
            ],
            'password' => [
                'bail', 'required', 'min:8',
            ],
            'phone_number' => [
                'required', 'numeric', 'unique:users,phone_number',
            ],
            'birthday' => [
                'bail', 'nullable', 'string',
                'date'
            ],
            // 'location' => [
            //     'bail', 'nullable', 'string', 'min:2',
            //     'max:20',
            // ],
            // 'roles' => 'nullable|array',
            'status' => 'nullable',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('add_admin');
    }
}
