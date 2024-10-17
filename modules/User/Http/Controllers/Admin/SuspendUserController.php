<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\SuspendUserRequest;
use Modules\User\Http\Resource\Admin\FullUserResource;
use Throwable;

class SuspendUserController extends Controller
{
    /**
     * Suspend the given user from the application
     *
     * @param SuspendUserRequest $request
     * @param User $user
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function suspend(SuspendUserRequest $request, User $user)
    {
        $this->suspendUser($user);

        return $this->dataResponse([
            'user' => new FullUserResource($user)
        ]);
    }

    /**
     * Enable the suspended flag for the given user in the database
     *
     * @param User $user
     * @return void
     * @throws Throwable
     */
    protected function suspendUser($user)
    {
        
        DB::transaction(function () use ($user) {
            $now = \Carbon\Carbon::now();
            $user->update([
                'status' => 'suspended',
                'status_updated_at' => $now 
            ]);
        });
    }
}
