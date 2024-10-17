<?php

namespace Modules\User\Http\Requests\PhoneNumber;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Entities\User\LoginPin;

class UpdatePhoneNumberRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'country_code' => [
                'required', 'integer', 'digits_between:1,4'
            ],
            'phone_number' => [
                'required', 'integer', 'digits_between:3,18'
            ],
            'pin' => [
                'required', 'numeric',
                'digits:' . LoginPin::PIN_LENGTH
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
