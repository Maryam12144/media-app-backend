<?php

namespace Modules\User\Http\Controllers\Admin\Members;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Role;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\Members\DeleteMemberRequest;
use Throwable;

class DeleteMemberController extends Controller
{
    /**
     * Remove an admin from website admins list
     *
     * @param DeleteMemberRequest $request
     * @param User $member
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function destroy(DeleteMemberRequest $request, User $member)
    {
        if (!$this->validAdminToRemove($member)) {
            return $this->returnCannotRemoveAdminResponse();
        }

        $this->removeMember($member);

        return $this->successResponse(
            __('member.members.removed')
        );
    }

    /**
     * Remove admin role from the given user
     *
     * @param User $member
     * @return void
     * @throws Throwable
     */
    protected function removeMember($member)
    {
        DB::transaction(function () use ($member) {
            $member->removeRole(Role::ROLE_USER);
            $member->delete();
        });
    }

    /**
     * Check if the given admin is valid to be removed
     *
     * @param User $member
     * @return bool
     */
    protected function validAdminToRemove(User $member)
    {
        if ($member->is_superadmin) {
            return false;
        }

        if (!$member->hasRole(Role::ROLE_USER)) {
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
