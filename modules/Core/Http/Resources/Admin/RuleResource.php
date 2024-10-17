<?php

namespace Modules\Core\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RuleResource extends JsonResource
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
            'rule' => $this->rule,
            'days' => $this->days,
            'minutes' => $this->minutes,
            'seconds' => $this->seconds,
            'flagged' => $this->flagged,
            'created_at' => $this->created_at,
        ];
    }
}
