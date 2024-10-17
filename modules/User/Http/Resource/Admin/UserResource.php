<?php

namespace Modules\User\Http\Resource\Admin;
use Modules\Core\Http\Resources\Admin\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Http\Resources\SimpleUserProfileResource;
use Modules\User\Entities;
/**
 * @package Labs\User\Http\Resources\Admin
 *
 * @OA\Schema(
 *      type="object",
 *      schema="PaginatedUserModelList",
 *
 *      @OA\Property(
 *          type="object",
 *          property="links",
 *          @OA\Property(property="prev", type="string", format="uri", nullable=true, example="http://api.example.org/accounts/?page=2"),
 *          @OA\Property(property="next", type="string", format="uri", nullable=true, example="http://api.example.org/accounts/?page=4"),
 *      ),
 *      @OA\Property(
 *          type="object",
 *          property="meta",
 *          @OA\Property(property="per_page", type="number", example="10"),
 *          @OA\Property(property="total", type="number", example="123"),
 *      ),
 *
 *      @OA\Property(
 *          type="array",
 *          property="data",
 *          @OA\Items(ref="#/components/schemas/UserResponse")
 *      ),
 * )
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // $userProfileId = Entities\UserProfile::where('user_id', $this->id)->pluck('id')->first();
        // $userProfile = Entities\UserProfile::find($userProfileId);
        return [
            'id' => $this->id,
            'email' => $this->email,
            // 'user_profile' => new SimpleUserProfileResource($userProfile),
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatar_url,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'phone_number' => $this->phone_number,
            'rating' => $this->rating,
            'status' => $this->status,
            'birthday' => $this->birthday
                // ? $this->birthday->toDateString()
                ? $this->birthday
                : null,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'is_superadmin' => $this->is_superadmin,
            'lang' => $this->lang,
            'roles' => RoleResource::collection($this->roles()->get()),
            'last_active_at' => $this->getLastActiveAt(),
            'verified_email' => (bool)$this->email_verified_at,
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the last activity time for user
     *
     * @return string|null
     */
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
}
