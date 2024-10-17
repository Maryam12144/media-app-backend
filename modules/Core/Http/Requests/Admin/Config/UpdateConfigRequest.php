<?php

namespace Modules\Core\Http\Requests\Admin\Config;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Entities\Config;
use Modules\Core\Rules\ValidConfigValue;

class UpdateConfigRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var Config $config */
        $config = $this->route('config');

        return [
            'value' => [
                new ValidConfigValue($config)
            ]
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

        return $user->can('change_config');
    }
}
