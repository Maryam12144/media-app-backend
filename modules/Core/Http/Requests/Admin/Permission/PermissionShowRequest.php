<?php

namespace Modules\Core\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PermissionShowRequest
 * @package Labs\Core\Http\Requests\Admin\Permission
 * @SWG\Definition(
 *     definition="Core@AdminPermissionShowRequest",
 *     description="permission:view_permission"
 * ),
 */
class PermissionShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('view_permissions');
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
