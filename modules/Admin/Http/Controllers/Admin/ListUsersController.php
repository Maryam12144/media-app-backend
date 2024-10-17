<?php

namespace Modules\User\Http\Controllers\Admin;

use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Controllers\Controller;
use Modules\User\Filters\Admin\UserFilter;
use Modules\User\Http\Requests\Admin\ListUsersRequest;
use Modules\User\Http\Resource\Admin\UserResource;
use Illuminate\Http\Request;
use Modules\Core\Entities\Role;

class ListUsersController extends Controller
{
    /**
     * Fetch the list of users
     *
     * @param ListUsersRequest $request
     * @param UserFilter $filter
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ListUsersRequest $request, UserFilter $filter)
    {
        $users = $this->getUsers($request,
            $filter);

        return UserResource::collection(
            $users
        );
    }

    public function UsersCount(Request $request)
    {
        $adminRoles = ['Admin', 'Normal Admin'];
        return User::whereHas('roles', static function ($query) use ($adminRoles) {
                return $query->whereIn('name', $adminRoles);
        })->count();
    }

    /**
     * Filter the users and return the results
     *
     * @param ListUsersRequest $request
     * @param $filter
     * @return LengthAwarePaginator
     */
    protected function getUsers($request, $filter)
    {
        $adminRoles = ['Admin', 'Normal Admin'];

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
