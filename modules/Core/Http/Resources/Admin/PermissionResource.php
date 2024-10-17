<?php

namespace Modules\Core\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PermissionResource
 * @package Labs\Core\Http\Resources\Admin
 */
class PermissionResource extends JsonResource
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
            'title' => $this->title,
            'group_name' => $this->group_name
        ];
    }
}
