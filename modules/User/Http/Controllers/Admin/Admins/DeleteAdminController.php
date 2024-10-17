<?php

namespace Modules\User\Http\Controllers\Admin\Admins;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Role;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\Admins\DeleteAdminRequest;
use Throwable;

class DeleteAdminController extends Controller
{
    /**
     * Remove an admin from website admins list
     *
     * @param DeleteAdminRequest $request
     * @param User $admin
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function destroy(DeleteAdminRequest $request, User $admin)
    {
        if (!$this->validAdminToRemove($admin)) {
            return $this->returnCannotRemoveAdminResponse();
        }

        $this->removeAdmin($admin);

        return $this->successResponse(
            __('admin.admins.removed')
        );
    }

    /**
     * Remove admin role from the given user
     *
     * @param User $admin
     * @return void
     * @throws Throwable
     */
    protected function removeAdmin($admin)
    {
        DB::transaction(function () use ($admin) {
            $admin->removeRole(Role::ROLE_ADMIN);

            $admin->removeRole(Role::ROLE_NORMAL_ADMIN);

            $admin->delete();
        });
    }

    /**
     * Check if the given admin is valid to be removed
     *
     * @param User $admin
     * @return bool
     */
    protected function validAdminToRemove(User $admin)
    {
        if ($admin->is_superadmin) {
            return false;
        }

        if (!$admin->hasRole(Role::ROLE_ADMIN)) { 
            return false;
        }

        return true;
    }

    /**
     * Return error response stating that user
     * cannot remove admin
     *
     * @return ResponseFactory|Response
     */
    protected function returnCannotRemoveAdminResponse()
    {
        return $this->errorResponse(
            __('admin.admins.cannot-remove')
        );
    }
}
