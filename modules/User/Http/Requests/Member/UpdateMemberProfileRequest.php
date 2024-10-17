<?php

namespace Modules\User\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\User\Entities\UserProfile;
use Auth;
class UpdateMemberProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();
        $user_profile_id = UserProfile::where('user_id', $user->id)->pluck('id')->first();
        return [
            'username' => ['required',
                Rule::unique('user_profiles')->ignore($user_profile_id)
            ],
            'bio' => 'required',
            'location' => 'required',
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
