<?php

namespace Modules\Core\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RoleDestroyRequest
 * @package Labs\Core\Http\Requests\Admin\Role
 * @SWG\Definition(
 *     definition="Core@AdminRoleDestroyRequest",
 *     description="permission:delete_roles"
 * ),
 */
class RoleDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete_roles');
    }

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
}
