<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SimpleUserProfileResource extends JsonResource
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
            'user_id' => $this->user_id,
            'username' => $this->username,
            'bio' => $this->bio,
            'location' => $this->location,
            'website_url' => $this->website_url,
            'credits' => $this->credits,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
