<?php

namespace Modules\User\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Entities;
use Modules\Transection\Entities\Transection;
use Modules\Transection\Entities\TransectionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 
use Modules\User\Http\Requests\Member as ProfileRequests;
use Modules\Core\Http\Resources\SelfUserResource;
use Modules\User\Http\Resources\SimpleUserProfileResource;
use Intervention\Image\Facades\Image;
use Throwable;

class UserProfileController extends Controller
{

    public function __construct(Entities\UserProfile $model)
    {
        $this->model = $model;
        $user = Auth::user();
        $this->user = $user;
    }

    public function info()
    {
        $userProfile = Entities\UserProfile::where('user_id', $this->user->id)->first();
        if(!$userProfile){
           return $this->notFound(); 
        }
        return $this->successResponse(
            new SimpleUserProfileResource($userProfile)
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ProfileRequests\StoreMemberProfileRequest $request)
    {
        $credits = $this->signupCreditsTransection($this->user->id);

        if($credits == 0){
                return $this->successResponse(
                    __('user.profile.credit-error'),
                );
        }
        
        $request->request->add([
            'user_id' => $this->user->id,
            'credits' => $credits,
        ]);

        $this->model->create($request->input());

        $this->user->update([
            'birthday' => $request->input('birthday'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
        ]);

        if ($file = $request->file('avatar')) {

            $avatarPath = public_path('/images/avatars/'.$this->user->id.'/');

            if ($avatar = $this->user->avatar) {
                File::delete($avatarPath.$avatar);
            }

            $avatar = $file->hashName();            
            $file->move($avatarPath, $avatar);

            $this->user->update([
                'avatar' => $avatar
            ]);

        }

        

        return $this->successResponse(
            __('user.profile.created'),
        );
    }

    public function signupCreditsTransection($user_id)
    {
        
        $sender = Entities\UserProfile::where('user_id', 1)->first();
        if($sender == null){
            return $credits = 0;
        }
        
        $receiver = Entities\UserProfile::where('user_id', $user_id)->first();

        $transectionLog = new TransectionLog;
        $transectionLog->sender_opening_balance = $sender->credits;
        $transectionLog->receiver_opening_balance = 0;
        $transectionLog->save();

        $transection = new Transection;
        $transection->sent_by = 1;
        $transection->received_by = $user_id;
        $transection->credits = 500;
        $transection->transection_type = 'signup';
        $transection->status = 'Completed'; 
        $transection->save();

        $sender->update([
            'credits' => $sender->credits - 500
        ]);
        

        $transectionLog->update([
            'transection_id' => $transection->id,
            'sender_closing_balance' => $sender->credits,
            'receiver_closing_balance' => 500
        ]);

        return $credits = 500;
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ProfileRequests\UpdateMemberProfileRequest $request)
    {

        $userProfileID = Entities\UserProfile::where('user_id', $this->user->id)->pluck('id')->first();
        $userProfile = Entities\UserProfile::findOrFail($userProfileID);
        $userProfile->update($request->input());

        $this->user->update([
            'birthday' => $request->input('birthday'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'city' => $request->input('city'),
        ]);
        

        /*
         * Update user avatar
         */
        $this->updateAvatar($this->user, $request);

        return $this->successResponse(
            __('user.profile.updated'), 
            [
                'user' => new SelfUserResource($this->user)
            ]
        );
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
