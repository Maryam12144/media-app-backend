<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,

            'device' => $this->device,
            'type' => $this->type,
            'ip' => $this->ip,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
