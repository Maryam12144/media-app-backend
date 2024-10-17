<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\UnsuspendUserRequest;
use Modules\User\Http\Resource\Admin\FullUserResource;
use Throwable;

class UnsuspendUserController extends Controller
{
    /**
     * Unsuspend the given user in the application
     *
     * @param UnsuspendUserRequest $request
     * @param User $user
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function unsuspend(UnsuspendUserRequest $request, User $user)
    {
        $this->unsuspendUser($user);

        return $this->dataResponse([
            'user' => new FullUserResource($user)
        ]);
    }

    /**
     * Disable the suspended flag for the given user in the database
     *
     * @param User $user
     * @return void
     * @throws Throwable
     */
    protected function unsuspendUser($user)
    {

        DB::transaction(function () use ($user) {
            $now = \Carbon\Carbon::now();
            $user->update([
                'status' => 'active',
                'status_updated_at' => $now
            ]);
        });
    }
}
