<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\ShowUserRequest;
use Modules\User\Http\Resource\Admin\FullUserResource;

class ShowUserController extends Controller
{
    /**
     * Fetch a single user from the database
     *
     * @param ShowUserRequest $request
     * @param User $user
     * @return ResponseFactory|Response
     */
    public function show(ShowUserRequest $request, User $user)
    {
        return $this->dataResponse([ 
            'user' => new FullUserResource($user)
        ]);
    }
}