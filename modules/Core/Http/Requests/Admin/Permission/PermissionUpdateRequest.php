<?php

namespace Modules\Core\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PermissionUpdateRequest
 * @package Labs\Core\Http\Requests\Admin\Permission
 * @SWG\Definition(
 *     definition="Core@AdminPermissionUpdateRequest",
 *     description="permission:update_permission"
 * ),
 */
class PermissionUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update_permissions');
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
