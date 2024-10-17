<?php

namespace Modules\News\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\News\Entities;
use Modules\Core\Http\Controllers\Controller;
use Modules\News\Http\Resources\GeographicalCriteriaResource;
use Modules\News\Http\Requests\GeographicalCriteria as GeographicalCriteriaRequests;

class GeographicalCriteriaController extends Controller
{
    public function __construct(Entities\GeographicalCriteria $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(GeographicalCriteriaRequests\GeographicalCriteriaShowRequest $request)
    {
        return GeographicalCriteriaResource::collection(
            Entities\GeographicalCriteria::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(GeographicalCriteriaRequests\GeographicalCriteriaStoreRequest $request)
    {
        $geographicalcriteria = $this->model->create($request->input());
            
        return $this->successResponse(
            trans('news::news.geographical-criteria.created'),
            new GeographicalCriteriaResource($geographicalcriteria)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(GeographicalCriteriaRequests\GeographicalCriteriaShowRequest $request, Entities\GeographicalCriteria $geographicalcriteria)
    {
        return new GeographicalCriteriaResource($geographicalcriteria);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(GeographicalCriteriaRequests\GeographicalCriteriaUpdateRequest $request, Entities\GeographicalCriteria $geographicalcriteria)
    {
        $geographicalcriteria->update($request->input());

        return $this->successResponse(
            trans('news::news.geographical-criteria.updated'),
            new GeographicalCriteriaResource($geographicalcriteria)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(GeographicalCriteriaRequests\GeographicalCriteriaDestroyRequest $request, Entities\GeographicalCriteria $geographicalcriteria)
    {
        $geographicalcriteria->delete();

        return $this->successResponse(
            trans('news::news.geographical-criteria.deleted')
        );
    }
}
