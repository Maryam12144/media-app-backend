<?php

namespace Modules\News\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\News\Entities;
use Modules\Core\Http\Controllers\Controller;
use Modules\News\Http\Resources\ProminenceResource;
use Modules\News\Http\Requests\Prominence as ProminenceRequests;

class ProminenceController extends Controller
{
    public function __construct(Entities\Prominence $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProminenceRequests\ProminenceShowRequest $request)
    {
        return ProminenceResource::collection(
            Entities\Prominence::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(ProminenceRequests\ProminenceStoreRequest $request)
    {
        $prominence = $this->model->create($request->input());
            
        return $this->successResponse(
            trans('news::news.prominence.created'),
            new ProminenceResource($prominence)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ProminenceRequests\ProminenceShowRequest $request, Entities\Prominence $prominence)
    {
        return new ProminenceResource($prominence);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(ProminenceRequests\ProminenceUpdateRequest $request, Entities\Prominence $prominence)
    {
        $prominence->update($request->input());

        return $this->successResponse(
            trans('news::news.prominence.updated'),
            new ProminenceResource($prominence)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProminenceRequests\ProminenceDestroyRequest $request, Entities\Prominence $prominence)
    {
        $prominence->delete();

        return $this->successResponse(
            trans('news::news.prominence.deleted')
        );
    }
}
