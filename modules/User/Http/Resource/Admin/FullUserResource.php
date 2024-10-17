<?php

namespace Modules\User\Http\Resource\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Http\Resources\Admin\RoleResource;
use Modules\Subscription\Http\Resources\Admin\PlanResource;
use Modules\User\Http\Resources\LoginHistoryResource;
use Modules\User\Http\Resources\UserInfoResource;
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
class FullUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        $userProfileId = Entities\UserProfile::where('user_id', $this->id)->pluck('id')->first();
        $userProfile = Entities\UserProfile::find($userProfileId);
        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'access_level' => $this->access_level,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'lang' => $this->lang,
            'email' => $this->email,
            'gender' => $this->gender,
            'user_profile' => new SimpleUserProfileResource($userProfile),
            'role' => count($this->roles)
                ? new RoleResource($this->roles[0])
                : null,
            'roles' => RoleResource::collection($this->roles),
            'last_login' => $this->getLastLogin(),
            'is_superadmin' => $this->is_superadmin,
            // 'suspended' => $this->suspended,
            'rating' => $this->rating,
            'status' => $this->status,
            'phone_number' => $this->phone_number,
            'created_at' => $this->created_at,
            'birthday' => $this->birthday
                // ? $this->birthday->toDateString()
                ? $this->birthday
                : null,
        ];
    }

    /**
     * Get the last login record of user
     *
     * @return array|null
     */
    protected function getLastLogin()
    {
        if (!count($this->loginHistories)) {
            return null;
        }

        return (new LoginHistoryResource($this->loginHistories[0]))
            ->toArray(request());
    }
}
