<?php

namespace Modules\User\Http\Controllers\Admin\Admins;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Role;
use Modules\Core\Events\UserRegistered;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\Admins\AddAdminRequest;
use Modules\User\Http\Resource\Admin\UserResource;
use Throwable;

class AddAdminController extends Controller
{
    /**
     * Add a new admin to the database
     *
     * @param AddAdminRequest $request
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function add(AddAdminRequest $request)
    {
        $admin = $this->createAdmin($request);

        return $this->successResponse(
            __('admin.admins.added'), [
                'admin' => new UserResource($admin)
            ]
        );
    }

    /**
     * Create a new admin based on the given details
     *
     * @param AddAdminRequest $request
     * @return User
     * @throws Throwable
     */
    protected function createAdmin($request)
    {
        DB::transaction(function () use (&$user, $request) {
            $user = User::createAdmin(
                $request->get('phone_number'),
                $request->get('email'),
                $request->get('password'),
                $request->get('first_name'),
                $request->get('last_name'),
            );

            event(new UserRegistered($user));

            $roles = $request->input('roles');
            $user->assignRole(Role::ROLE_ADMIN);
            foreach ($roles as $key => $role) {      
                $role = Role::where('id', $role)->pluck('name')->first();  

                $user->assignRole($role);
            }

            // $user->assignRole(Role::ROLE_ADMIN);
            // $user->assignRole(Role::ROLE_NORMAL_ADMIN);
                
        });

        return $user->fresh();
    }
}
