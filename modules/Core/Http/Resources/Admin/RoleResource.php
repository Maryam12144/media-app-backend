<?php

namespace Modules\Core\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class RoleResource
 * @package Labs\Core\Http\Resources\Admin
 */
class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'permissions' => PermissionResource::collection($this->permissions),
        ];
    }
}
