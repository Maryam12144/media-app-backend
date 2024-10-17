<?php 

namespace Modules\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Core\Entities;
use Modules\Core\Http\Requests\Admin\Permission as PermissionRequests;
use Modules\Core\Http\Requests\Admin\Role as RoleRequests;
use Modules\Core\Http\Resources\Admin\RoleResource;
use Illuminate\Http\Request;
/**
 * Class RoleController
 * @property Entities\Role model
 * @package Labs\Core\Http\Controllers
 * @resource Admin Roles
 *
 */
class RoleController extends Controller
{
    /**
     * TestController constructor.
     * @param Entities\Role $model
     */
    public function __construct(Entities\Role $model)
    {
        $this->model = $model;
    }

    /**
     * @SWG\Get(
     *     path="/admin/roles",
     *     tags={"Admin Roles"},
     *     summary="Get Roles List",
     *     @SWG\Response(
     *          response=200,
     *          description="ORACLE Fluent Query Response.",
     *      ),
     * ),
     * @param RoleRequests\RoleShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RoleRequests\RoleShowRequest $request)
    {
        return RoleResource::collection(
            Entities\Role::all()
        );
    }

    /**
     * @SWG\Get(
     *     path="/admin/roles/{role}",
     *     tags={"Admin Roles"},
     *     summary="Get Role",
     *     @SWG\Parameter(
     *          name="role",
     *          in="path",
     *          description="UUID",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Parameter(
     *          name="user",
     *          in="body",
     *          required=true,
     *          type="string",
     *          schema={"$ref": "#/definitions/Core@AdminRoleShowRequest"},
     *        ),
     *     @SWG\Response(
     *          response=200,
     *          description="RoleResource.",
     *      ),
     * ),
     * @param RoleRequests\RoleShowRequest $request
     * @param Entities\Role $role
     * @return RoleResource
     */
    public function show(RoleRequests\RoleShowRequest $request, $role)
    {
        $role = Entities\Role::findOrFail($role);
        return new RoleResource($role);
    }

    /**
     * @SWG\Post(
     *     path="/admin/roles",
     *     tags={"Admin Roles"},
     *     summary="Create New Role",
     *     @SWG\Parameter(
     *          name="role",
     *          in="body",
     *          required=true,
     *          type="string",
     *          schema={"$ref": "#/definitions/Core@AdminRoleStoreRequest"},
     *        ),
     *     @SWG\Response(
     *          response=200,
     *          description="RoleResource.",
     *      ),
     * ),
     * @param RoleRequests\RoleStoreRequest $request
     * @return RoleResource
     */
    public function store(RoleRequests\RoleStoreRequest $request)
    {
        $model = $this->model->create($request->input());

        return new RoleResource($model);
    }


    /**
     * @SWG\Put(
     *     path="/admin/roles/{role}",
     *     tags={"Admin Roles"},
     *     summary="Update Role",
     *     @SWG\Parameter(
     *          name="role",
     *          in="path",
     *          description="UUID",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Parameter(
     *          name="user",
     *          in="body",
     *          required=true,
     *          type="string",
     *          schema={"$ref": "#/definitions/Core@AdminRoleUpdateRequest"},
     *        ),
     *     @SWG\Response(
     *          response=200,
     *          description="RoleResource.",
     *      ),
     * ),
     * @param Entities\Role $role
     * @param RoleRequests\RoleUpdateRequest $request
     * @return RoleResource
     */
    public function update(RoleRequests\RoleUpdateRequest $request, $role)
    {
        $role = Entities\Role::findOrFail($role);
        $role->update($request->input());

        return new RoleResource($role);
    }


    /**
     * @SWG\Delete(
     *     path="/admin/roles/{role}",
     *     tags={"Admin Roles"},
     *     summary="Delete Role",
     *     @SWG\Parameter(
     *          name="role",
     *          in="path",
     *          description="UUID",
     *          required=true,
     *          type="string"
     *      ),
     *     @SWG\Response(
     *          response=200,
     *          description="RoleResource.",
     *      ),
     * ),
     * @param RoleRequests\RoleDestroyRequest $request
     * @param Entities\Role $role
     * @return RoleResource
     * @throws \Exception
     */
    public function destroy(RoleRequests\RoleDestroyRequest $request, $role)
    {
        $role = Entities\Role::findOrFail($role);
        $role->delete();

        return new RoleResource($role);
    }

    /**
     * Update Permissions
     * **Method: Labs\Core\Http\Controllers\Admin\RoleController@updatePermissions **
     * @param PermissionRequests\PermissionUpdateRequest $request
     * @param Entities\Role $role
     * @return RoleResource
     */
    public function updatePermissions(Request $request, $role)
    {
        $role = Entities\Role::findOrFail($role);
        
        return new RoleResource($role->syncPermissions($this->getRequestPermissionsKeys($request)));
    }

    /**
     * @param PermissionRequests\PermissionUpdateRequest $request
     * @return static
     */
    private function getRequestPermissionsKeys(Request $request)
    {
        return collect($request->input())->filter(function ($permission) {
            return $permission;
        })->keys();
    }
}