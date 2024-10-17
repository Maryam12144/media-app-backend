<?php

namespace Modules\Core\Http\Requests\Admin\Log;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class LogShowRequest
 * @package Labs\Core\Http\Requests\Admin\Log
 * @SWG\Definition(
 *     definition="Core@AdminLogShowRequest",
 *     description="null"
 * ),
 */
class LogShowRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
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
