<?php

namespace Modules\News\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\News\Entities;
use Modules\Core\Http\Controllers\Controller;
use Modules\News\Http\Resources\HumanInterestResource;
use Modules\News\Http\Requests\HumanInterest as HumanInterestRequests;

class HumanInterestController extends Controller
{
    public function __construct(Entities\HumanInterest $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(HumanInterestRequests\HumanInterestShowRequest $request)
    {
        return HumanInterestResource::collection(
            Entities\HumanInterest::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(HumanInterestRequests\HumanInterestStoreRequest $request)
    {
        $humaninterest = $this->model->create($request->input());
            
        return $this->successResponse(
            trans('news::news.human-interest.created'),
            new HumanInterestResource($humaninterest)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(HumanInterestRequests\HumanInterestShowRequest $request, Entities\HumanInterest $humaninterest)
    {
        return new HumanInterestResource($humaninterest);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(HumanInterestRequests\HumanInterestUpdateRequest $request, Entities\HumanInterest $humaninterest)
    {
        $humaninterest->update($request->input());

        return $this->successResponse(
            trans('news::news.human-interest.updated'),
            new HumanInterestResource($humaninterest)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(HumanInterestRequests\HumanInterestDestroyRequest $request, Entities\HumanInterest $humaninterest)
    {
        $humaninterest->delete();

        return $this->successResponse(
            trans('news::news.human-interest.deleted')
        );
    }
}
