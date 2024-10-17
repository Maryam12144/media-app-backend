<?php

namespace Modules\Core\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class RoleUpdateRequest
 * @package Labs\Core\Http\Requests\Admin\Role
 * @SWG\Definition(
 *     definition="Core@AdminRoleUpdateRequest",
 *     description="permission:update_roles",
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
class RoleUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update_roles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:255', Rule::unique('roles')->where('guard_name', $this->guard_name)->ignore($this->id)],
            'guard_name' => ['required', Rule::in('api', 'web')]
        ];
    }
}
