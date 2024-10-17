<?php

namespace Modules\User\Http\Controllers;

use App\User;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Entities\Contract;
use Modules\Core\Http\Controllers\Controller;

/**
 * Class UserSummaryController
 * @package Zix\PluginName\Http\Controllers
 */
class UserSummaryController extends Controller
{
    /**
     * Get a summary of user's profile
     *
     * @return ResponseFactory|Response
     */
    public function summary()
    {
        $user = Auth::user();

        return $this->dataResponse([
            'active_contracts_count' => $this->getActiveContractsCount($user),
            'completed_contracts_count' => $this->getCompletedContractsCount($user),
            'ratings_given_count' => $this->getRatingsGivenCount($user),
            'ratings_received_count' => $this->getRatingsReceivedCount($user),
            'unread_notifications_count' => $this->getUnreadNotificationsCount($user),
        ]);
    }

    /**
     * Get the number of active contracts for user
     *
     * @param User $user
     * @return int
     */
    protected function getActiveContractsCount(User $user)
    {
        return Contract::query()
            ->user($user)
            ->live()
            ->count();
    }

    /**
     * Get the number of completed contracts for user
     *
     * @param User $user
     * @return int
     */
    protected function getCompletedContractsCount(User $user)
    {
        return Contract::query()
            ->user($user)
            ->done()
            ->count();
    }

    /**
     * Get the number of ratings given by user
     *
     * @param User $user
     * @return int
     */
    protected function getRatingsGivenCount(User $user)
    {
        $ratingsGiven = $user->ratingsGiven()
            ->select([
                'creator_id',
                'target_user_id'
            ])
            ->groupBy('target_user_id');

        $countResult = DB::query()
            ->selectRaw('count(*) as total')
            ->fromSub($ratingsGiven->getQuery()->getQuery(), 'sub')
            ->first();

        return $countResult->total;
    }

    /**
     * Get the number of ratings received by user
     *
     * @param User $user
     * @return int
     */
    protected function getRatingsReceivedCount(User $user)
    {
        return $user->received_ratings_count;
    }

    /**
     * Get the number of unread notifications of user
     *
     * @param User $user
     * @return int
     */
    protected function getUnreadNotificationsCount(User $user)
    {
        return $user->unreadNotifications()
            ->count();
    }
}
