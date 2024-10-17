<?php

namespace Modules\Core\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class RoleStoreRequest
 * @package Labs\Core\Http\Requests\Admin\Role
 * @SWG\Definition(
 *     definition="Core@AdminRoleStoreRequest",
 *     description="permission:create_roles",
 *     required={"name", "guard_name"},
 *     @SWG\Property(
 *          property="name",
 *          type="text",
 *          description="Role Name",
 *          example="Customer"
 *    ),
 *     @SWG\Property(
 *          property="guard_name",
 *          type="text",
 *          description="Guard Name",
 *          example="api"
 *    ),
 * ),
 */
class RoleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create_roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:255', Rule::unique('roles')->where('guard_name', $this->guard_name)],
            'guard_name' => ['required', Rule::in('api', 'web')]
        ];
    }
}
