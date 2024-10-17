<?php

namespace Modules\News\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Modules\News\Entities;
use Modules\Core\Http\Controllers\Controller;
use Modules\News\Http\Resources\NewsTypeResource;
use Modules\News\Http\Requests\NewsType as NewsTypeRequests;

class NewsTypeController extends Controller
{
    public function __construct(Entities\NewsType $model)
    {
        $this->model = $model;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(GenreRequests\GenreShowRequest $request)
    {
        return NewsTypeResource::collection(
            Entities\Genre::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $newsType = $this->model->create($request->input());
            
        return $this->successResponse(
            trans('news::news.news-type.created'),
            new NewsTypeResource($newsType)
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(GenreRequests\GenreShowRequest $request, Entities\Genre $genre)
    {
        return new NewsTypeResource($genre);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(GenreRequests\GenreUpdateRequest $request, Entities\Genre $genre)
    {
        
        $genre->update($request->input());

        return $this->successResponse(
            trans('news::news.genre.updated'),
            new NewsTypeResource($genre)
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(GenreRequests\GenreDestroyRequest $request, Entities\Genre $genre)
    {
        $genre->delete();

        return $this->successResponse(
            trans('news::news.genre.deleted')
        );
    }
}