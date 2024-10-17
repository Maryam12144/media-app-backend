<?php

namespace Modules\Core\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PermissionDestroyRequest
 * @package Labs\Core\Http\Requests\Admin\Permission
 * @SWG\Definition(
 *     definition="Core@AdminPermissionDestroyRequest",
 *     description="permission:delete_permission"
 * ),
 */
class PermissionDestroyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete_permissions');
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
