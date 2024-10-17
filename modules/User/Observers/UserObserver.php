<?php

namespace Modules\User\Observers;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Core\Libraries\Option;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     *
     * @param User $user
     * @return void
     */
    public function creating(User $user)
    {
        // $user->trust_score = $user->trust_score ??
        //     Option::defaultStartingTrustScore();

        $user->password = $user->password
            ?? Hash::make('12345678');
    }

    /**
     * Handle the User "saving" event.
     *
     * @param User $user
     * @return void
     */
    public function saving(User $user)
    {
        $user->full_name = "$user->first_name $user->last_name";
    }


    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        User::resetUserCache($user->id);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        User::resetUserCache($user->id);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        User::resetUserCache($user->id);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        User::resetUserCache($user->id);
    }
}
