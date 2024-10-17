<?php

namespace Modules\User\Http\Controllers\Admin\Profile;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\Profile\UpdateProfileRequest;

class UpdateProfileController extends Controller
{
    /**
     * Change own login password
     *
     * @param UpdateProfileRequest $request
     * @return ResponseFactory|Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        /*
         * Update user profile based on the given input
         */
        $this->updateProfile($user,
            $request);

        /*
         * Return success response
         */
        return $this->successResponse(
            __('admin.profile.updated')
        );
    }

    /**
     * Update the given fields of the user
     *
     * @param User $user
     * @param UpdateProfileRequest $request
     */
    protected function updateProfile($user, $request)
    {
        $user->fill(array_filter(
            $request->validated()
        ));

        if ($newPassword = $request->get('password')) {
            $user->fill([
                'password' => Hash::make($newPassword)
            ]);
        }

        return $user->save();
    }
}
