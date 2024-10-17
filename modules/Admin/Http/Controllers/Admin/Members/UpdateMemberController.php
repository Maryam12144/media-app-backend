<?php

namespace Modules\User\Http\Controllers\Admin\Members;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Http\Requests\Admin\Members\UpdateMemberRequest;
use Modules\User\Http\Resource\Admin\FullUserResource;
use Modules\User\Entities\UserProfile;
use Throwable;

class UpdateMemberController extends Controller
{
     /** 
     * Update a single user in the database
     *
     * @param UpdateMemberRequest $request
     * @param User $member
     * @return ResponseFactory|Response
     * @throws Throwable
     */
    public function update(UpdateMemberRequest $request, User $member)
    {
        $this->updateMember($member, $request);
        $this->updateMemberProfile($member, $request);

        return $this->dataResponse([
            'user' => new FullUserResource($member)
        ]);
    }

    /**
     * Apply the requested changes on the given user
     *
     * @param User $member
     * @param UpdateMemberRequest $request
     * @return void
     * @throws Throwable
     */
    protected function updateMember($member, $request)
    {
        /*
         * First apply the values that cannot be
         * set to null
         */ 
        // dd($request->all());
        $member->fill(array_filter($request->validated(),
            function ($item) {
                return !is_null($item);
            }
        ));

        if ($password = $request->get('password')) {
            $member->fill([
                'password' => Hash::make($password)
            ]);
        }

        /*
         * Then apply the nullable attributes
         */
        $member->fill($request->only([
            'country', 'state', 'city', 'birthday', 'status'
        ]));

        DB::transaction(function () use ($member) {
            $member->save();
        });
    }

    public function updateMemberProfile($member, $request)
    {
        $userMember = UserProfile::where('user_id', $member->id)->first();
        if($userMember){
            $userMember->fill($request->only([
                'location', 'credits'
            ]));

            DB::transaction(function () use ($userMember) {
                $userMember->save();
            });
        }
            
    }
}
