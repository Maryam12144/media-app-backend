<?php

namespace Modules\Core\Http\Requests\User;


use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AdminLoginRequest
 */
class AdminLoginRequest extends FormRequest
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
            'email' => [
                'required', 'string', 'email',
                'exists:users,email'
            ],
            'password' => [
                'required', 'string'
            ],
        ];
    }
}