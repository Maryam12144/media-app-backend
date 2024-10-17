<?php

namespace Modules\Core\Http\Controllers\Admin;

use Modules\Core\Entities;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Http\Requests\Admin\Rule as RuleRequests;
use Modules\Core\Http\Resources\Admin\RuleResource;

class RuleController extends Controller
{

    public function __construct(Entities\Rule $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(RuleRequests\RuleShowRequest $request)
    {
        return $this->dataResponse([
            RuleResource::collection(
                Entities\Rule::all()
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(RuleRequests\RuleUpdateRequest $request, $rule)
    {
        $rule = Entities\Rule::findOrFail($rule);
        $rule->update($request->input());

        return $this->successResponse( 
            __('rule.updated'),
            [
                'rules' => new RuleResource($rule)
            ]
        );
    }
}
