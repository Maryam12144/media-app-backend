<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Role;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\UpdateUserRequest;
use Modules\User\Http\Resource\Admin\FullUserResource;
use Throwable;

class UpdateUserController extends Controller
{
    /**
     * Update a single user in the database
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->updateUser($user, $request);

        return $this->dataResponse([
            'user' => new FullUserResource($user)
        ]);
    }

    /**
     * Apply the requested changes on the given user
     *
     * @param User $user
     * @param UpdateUserRequest $request
     * @return void
     * @throws Throwable
     */
    protected function updateUser($user, $request)
    {
        /*
         * First apply the values that cannot be
         * set to null
         */
        $user->fill(array_filter($request->validated(),
            function ($item) {
                return !is_null($item);
            }
        ));

        if ($password = $request->get('password')) {
            $user->fill([
                'password' => Hash::make($password)
            ]);
        }

        /*
         * Then apply the nullable attributes
         */
        $user->fill($request->only([
            'country', 'state', 'city', 'birthday', 'status'
        ]));


        $roles = $request->input('roles');
        if($roles){
            $user->roles()->detach();
            $user->assignRole(Role::ROLE_ADMIN);
            foreach ($roles as $key => $role) {      
                $role = Role::where('id', $role)->pluck('name')->first();  
                $user->assignRole($role);
            }
        }
        
        DB::transaction(function () use ($user) {
            $user->save();
        });

    }
}
