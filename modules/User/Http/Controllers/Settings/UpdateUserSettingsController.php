<?php

namespace Modules\User\Http\Controllers\Settings;

use App\User;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Resources\SelfUserResource;
use Modules\User\Http\Requests\Settings\UpdateUserSettingsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UpdateUserSettingsController extends Controller
{
    /**
     * Update user settings based on the given input
     *
     * @param UpdateUserSettingsRequest $request
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if($request->file('file')){
            $request['avatar'] = Storage::disk('digitalocean')->putFile('uploads', $request->file('file'),'public');
        }
        $user->update($request->input());
        return $user;
        // $this->updateUser($user, $request);

        // return $this->successResponse(
        //     __('user.settings.updated'), [
        //         'user' => new SelfUserResource($user)
        //     ]
        // );
    }

    /**
     * Apply the requested changes on the user
     * and save the changes
     *
     * @param User $user
     * @param UpdateUserSettingsRequest $request
     * @return void
     * @throws Throwable
     */
    protected function updateUser($user, $request)
    {
        return '$user';
        DB::transaction(function () use ($user, $request) {
            /*
             * Apply the changes and remove the null values
             * that cannot be set to null
             */
            $user->update(array_filter(
                $request->validated(), function ($item, $key) {
                // these keys can accept null values to apply on the user model
                $nullableAttributes = [
                    'location', 'birthday',
                    'country', 'state', 'city'
                ];

                return
                    (
                        // it should not be empty
                        !is_null($item)

                        // unless it exists in the nullable attributes
                        || in_array($key, $nullableAttributes)
                    )
                    // and exclude the following attributes
                    && !in_array($key, [
                        'avatar', 'password'
                    ]);
            }, ARRAY_FILTER_USE_BOTH));

            /*
             * Update user avatar
             */
            $this->updateAvatar($user, $request);

           /*
            * Update password if requested
            */
           $this->updatePassword($user, $request);
        });
    }


    /**
     * Update avatar on the user model,
     * and delete if null passed
     *
     * @param User $user
     * @param UpdateUserSettingsRequest $request
     * @return void|mixed
     * @throws Exception
     */
    protected function updateAvatar(User $user, $request)
    {

        if (!$request->has('avatar')) return;

        if (!$request->avatar) {
            return $user->removeAvatar();
        }

        try {
            $image = $this->optimizeImage(
                Image::make($request->avatar)
            );
        } catch (Throwable $exception) {
            return $this->returnInvalidImageProvidedResponse();
        }

        return $user->updateAvatar(
            $image
        );
    }

   /**
    * Update user password if provided
    *
    * @param User $user
    * @param UpdateUserSettingsRequest $request
    * @return
    */
   public function updatePassword($user, $request)
   {
       if ($newPassword = $request->get('password')) {
           $user->updatePassword($newPassword);
       }
   }

    /**
     * Return an error stating that the image format is incorrect
     *
     * @return ResponseFactory|Response
     */
    protected function returnInvalidImageProvidedResponse()
    {
        return $this->errorResponse(
            __('user.settings.invalid-avatar')
        );
    }

    /**
     * Optimize the given image
     *
     * @param \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    protected function optimizeImage($image)
    {
        $image->fit(400, 400);

        $image->encode('jpg', 60);

        return $image;
    }
}
