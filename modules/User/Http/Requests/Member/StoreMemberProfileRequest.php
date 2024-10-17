<?php

namespace Modules\User\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:user_profiles,username',
            'bio' => 'required',
            'location' => 'required',
            'birthday' => [
                'bail', 'nullable', 'string',
                'date'
            ],
            // 'country' => 'required',
            // 'state' => 'required',
            // 'city' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

    public function messages()
    {
        return [
            // 'title.required' => 'Title required',
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
