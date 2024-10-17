<?php

namespace Modules\User\Http\Requests\Admin;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UnsuspendUserRequest
 *
 * @package Labs\User\Http\Requests\Admin
 */
class UnsuspendUserRequest extends FormRequest
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
        /** @var User $user */
        $user = $this->user();

        return $user->can('update_users');
    }
}
