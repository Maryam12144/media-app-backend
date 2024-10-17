<?php

namespace Modules\Core\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RoleShowRequest
 * @package Labs\Core\Http\Requests\Admin\Role
 * @SWG\Definition(
 *     definition="Core@AdminRoleShowRequest",
 *     description="permission:view_roles"
 * ),
 */
class RoleShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // dd($this->user()->can('view_roles'));
        return $this->user()->can('view_roles');
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
