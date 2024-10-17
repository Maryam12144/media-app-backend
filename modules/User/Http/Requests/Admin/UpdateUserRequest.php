<?php

namespace Modules\User\Http\Requests\Admin;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
/**
 * Class UpdateUserRequest
 *
 * @package Labs\User\Http\Requests\Admin
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->user->id);
        return [
            'first_name' => [
                'bail', 'nullable', 'string',
                'min:2', 'max:25',
            ],
            'last_name' => [
                'bail', 'nullable', 'string',
                'min:2', 'max:25'
            ],
            'email' => [
                'bail', 'nullable', 'string',
                'email', 'max:100',
                Rule::unique('users')->ignore($this->user->id)
            ],
            'password' => [
                'bail', 'nullable', 'string', 'min:8',
            ],
            'phone_number' => [
                'required', 'numeric',
                Rule::unique('users')->ignore($this->user->id)
            ],
            'birthday' => [
                'bail', 'nullable', 'string',
                'date'
            ],
            'country' => [
                'bail', 'nullable', 'string',
                'min:2', 'max:24'
            ],
            'state' => [
                'bail', 'nullable', 'string',
                'min:2', 'max:24'
            ],
            'city' => [
                'bail', 'nullable', 'string',
                'min:2', 'max:24'
            ],
            'roles' => 'nullable|array',
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
        /** @var User $loggedIn */
        $loggedIn = $this->user();

        /** @var User $targetUser */
        $targetUser = $this->route('user');

        if ($targetUser->is_superadmin) {
            return $loggedIn->is($targetUser);
        }

        if ($targetUser->isAdmin()) {
            return $loggedIn->is_superadmin;
        }

        return true;
    }
}
