<?php

namespace Modules\Core\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Admin\RoleResource;
use Modules\User\Http\Resources\SimpleUserProfileResource;
use Modules\User\Entities;
/**
 * Class UserResource
 * @package Labs\Core\Http\Resource
 */
class SelfUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($requestd)
    {
        return [
            'id' => $this->id,
            'phone_number' => $this->phone_number,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatar_url,
            'rating' => $this->rating,
            'status' => $this->status,
            // 'role' => new RoleResource($this->roles()->first()),
            'roles' => RoleResource::collection($this->roles()->get()),
            'created_at' => $this->created_at,
            'last_active_at' => $this->getLastActiveAt(),
            'verified_email' => (bool)$this->email_verified_at,
            'is_superadmin' => (bool)$this->is_superadmin,
            'lang' => $this->lang,

            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,

            'birthday' => $this->birthday
                // ? $this->birthday->toDateString()
                ? $this->birthday
                : null,
        ];
    }

//    /**
//     * Get the subscription of user
//     *
//     * @return mixed
//     */
//    protected function getSubscription()
//    {
//        $subscription = $this->subscription();
//
//        if (!$subscription || $subscription->cancelled()) return null;
//
//        return $subscription;
//    }

    protected function getLastActiveAt()
    {
        if (!$this->relationLoaded('lastAccess') || !$this->lastAccess) return null;

        if ($this->lastAccess->last_used_at) {
            $lastAccessTime = $this->lastAccess->last_used_at;
        } else {
            $lastAccessTime = $this->lastAccess->created_at;
        }

        return $lastAccessTime->toDateTimeString();
    }

    /**
     * Get user's QR code in base64 encoded format
     *
     * @return string
     */
    protected function getQrCode()
    {
        return base64_encode($this->qr_code);
    }
}
