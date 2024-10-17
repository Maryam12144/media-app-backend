<?php

namespace Modules\News\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\News\Entities;
use Modules\Core\Http\Controllers\Controller;
use Modules\News\Http\Resources\VideoTypeResource;
use Modules\News\Http\Requests\VideoType as VideoTypeRequests;

class VideoTypeController extends Controller
{
    public function __construct(Entities\VideoType $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(VideoTypeRequests\VideoTypeShowRequest $request)
    {
        return VideoTypeResource::collection(
            Entities\VideoType::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(VideoTypeRequests\VideoTypeStoreRequest $request)
    {
        $videotype = $this->model->create($request->input());
            
        return $this->successResponse(
            trans('news::news.video-type.created'),
            new VideoTypeResource($videotype)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(VideoTypeRequests\VideoTypeShowRequest $request, Entities\VideoType $videotype)
    {
        return new VideoTypeResource($videotype);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(VideoTypeRequests\VideoTypeUpdateRequest $request, Entities\VideoType $videotype)
    {
        $videotype->update($request->input());

        return $this->successResponse(
            trans('news::news.video-type.updated'),
            new VideoTypeResource($videotype)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(VideoTypeRequests\VideoTypeDestroyRequest $request, Entities\VideoType $videotype)
    {
        $videotype->delete();

        return $this->successResponse(
            trans('news::news.video-type.deleted')
        );
    }
}
