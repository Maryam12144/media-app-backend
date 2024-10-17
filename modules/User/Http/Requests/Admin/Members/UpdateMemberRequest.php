<?php

namespace Modules\User\Http\Requests\Admin\Members;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateMemberRequest extends FormRequest
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
                'required', 'string', 'max:20'
            ],
            'last_name' => [
                'required', 'string', 'max:20'
            ],

            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($this->member->id),
                'max:100'
            ],

            'phone_number' => [
                'required', 'numeric',
                Rule::unique('users')->ignore($this->member->id)
            ],
            'birthday' => [
                'bail', 'nullable', 'string',
                'date'
            ],
            'location' => [
                'bail', 'nullable', 'string', 'min:2',
                'max:20',
            ],
            'credit' => [
                'bail', 'nullable', 'numeric'
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
        /** @var User $user */
        $user = $this->user();

        return $user->can('update_users');
    }
}
