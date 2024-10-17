<?php

namespace Modules\User\Http\Requests\Admin\Members;

use Illuminate\Foundation\Http\FormRequest;

class AddMemberRequest extends FormRequest
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
                'required', 'email', 'unique:users,email',
                'max:100'
            ],

            'phone_number' => [
                'required', 'numeric', 'unique:users,phone_number'
            ],

            'dob' => [
                'required',
            ],

            'status' => [
                'nullable',
            ],

            'rating' => [
                'required', 'numeric', 'min:0', 'max:10',
            ],

            'password' => [
                'required', 'min:8', 'max:100'
            ],

            'location' => [
                'required', 'string', 'min:2', 'max:150',
            ],

            'credits' => [
                'required', 'numeric', 'min:0',
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

        return $user->can('create_users');
    }
}
