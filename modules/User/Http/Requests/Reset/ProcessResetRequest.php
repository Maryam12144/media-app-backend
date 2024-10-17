<?php

namespace Modules\User\Http\Requests\Reset;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Entities\User\LoginPin;

class ProcessResetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
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
