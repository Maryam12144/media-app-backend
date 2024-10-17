<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Filters\Admin\UserFilter;
use Modules\User\Http\Requests\Admin\ListUsersRequest;
use Modules\User\Http\Resource\Admin\UserResource;
use Modules\User\Entities\LoginHistory;
use Carbon\Carbon;

class ListMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function membersCount(Request $request)
    {

        return $this->dataResponse([
            'total_users' => $this->totalMembers(),
            'new_users' => $this->newMembers(),
            'monthly_active_users' => $this->monthlyActiveUsers(),
                
         ]);
      
    }
    public function totalMembers()
    {
        $adminRoles = ['Member'];
        $totalMembers = User::whereHas('roles', static function ($query) use ($adminRoles) {
                return $query->whereIn('name', $adminRoles);
        })->count();

        return [
            'total' => $totalMembers,
        ];
    }

    public function newMembers()
    {
        $roles = ['Member'];
        
        $thisMonth = User::whereHas('roles', static function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
        })->whereMonth('created_at', Carbon::now()->month)->count();

        $previousMonth = User::whereHas('roles', static function ($query) use ($roles) {
                return $query->whereIn('name', $roles);
        })->whereMonth('created_at', Carbon::now()->subMonth())->count();

        if($previousMonth < $thisMonth){
            if($previousMonth >0){
                $difference = $thisMonth - $previousMonth;
                $percent = $difference / $thisMonth * 100; //increase percent
                $status = 'up';
            }else{
                $percent = 100; //increase percent
                $status = 'up';
            }
        }else if($previousMonth > $thisMonth){
            $difference = $previousMonth -$thisMonth;
            $percent = $difference / $previousMonth * 100; //decrease percent
            $status = 'down';
        }else{
            $percent = 0; //increase percent
            $status = 'equal';
        }

        return [
                'this_month' => $thisMonth,
                'previous_month' => $previousMonth,
                'percent' => $percent,
                'status' => $status,
            ];
    }

    public function monthlyActiveUsers()
    {
        $thisMonth = LoginHistory::where('type', 'regular')->whereMonth('created_at', Carbon::now()->month)->count();
        $previousMonth = LoginHistory::where('type', 'regular')->whereMonth('created_at', Carbon::now()->subMonth())->count();

        if($previousMonth < $thisMonth){
            if($previousMonth >0){
                $difference = $thisMonth - $previousMonth;
                $percent = $difference / $thisMonth * 100; //increase percent
                $status = 'up';
            }else{
                $percent = 100; //increase percent
                $status = 'up';
            }
        }else if($previousMonth > $thisMonth){
            $difference = $previousMonth -$thisMonth;
            $percent = $difference / $previousMonth * 100; //decrease percent
            $status = 'down';
        }else{
            $percent = 0; //increase percent
            $status = 'equal';
        }

        return [
                'this_month' => $thisMonth,
                'previous_month' => $previousMonth,
                'percent' => $percent,
                'status' => $status,
            ];
    }

    public function index(ListUsersRequest $request, UserFilter $filter)
    {

        $users = $this->getMembers($request,
            $filter);

        return UserResource::collection(
            $users
        );
    }

    protected function getMembers($request, $filter)
    {
        $adminRoles = ['Member'];
        $users = User::query()
            ->with([
                'roles.permissions',
                'lastAccess'
            ])->whereHas('roles', static function ($query) use ($adminRoles) {
                return $query->whereIn('name', $adminRoles);
            })->filter($filter);

        return $users
            ->jsonPaginate();
    }
}
