<?php

namespace Modules\Core\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     type="object",
 *     schema="AuthResponse",
 *     required={"data"},
 *     @OA\Property(
 *          property="data",
 *          type="object",
 *          required={"token"},
 *          @OA\Property(
 *              property="token",
 *              type="string",
 *          ),
 *          @OA\Property(
 *              property="user",
 *              type="object",
 *          ),
 *    ),
 * ),
 */
class AuthenticationResource extends JsonResource
{
    /**
     * @var string $token
     */
    public $token;

    public function __construct($resource, $token)
    {
        parent::__construct($resource);

        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'token' => $this->token,
            'user' => new SelfUserResource($this)
        ];
    }
}