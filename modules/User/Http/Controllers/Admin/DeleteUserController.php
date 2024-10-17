<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\DeleteUserRequest;
use Throwable;

class DeleteUserController extends Controller
{
    /**
     * Delete a single user
     *
     * @param DeleteUserRequest $request
     * @param User $user
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function destroy(DeleteUserRequest $request, User $user)
    {
        $this->deleteUser($user);

        return $this->successResponse();
    }

    /**
     * Delete the given user from the database
     *
     * @param User $user
     * @return void
     * @throws Throwable
     */
    protected function deleteUser($user)
    {
        DB::transaction(function () use ($user) {
            $user->delete();
        });
    }
}
