<?php

namespace Modules\Core\Libraries;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatorResourceCollection extends ResourceCollection
{
    /**
     * @var string|JsonResource
     */
    protected $class;

    /**
     * PaginatorResourceCollection constructor.
     *
     * @param $resource
     * @param $class
     */
    public function __construct($resource, $class)
    {
        parent::__construct($resource);

        $this->class = $class;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->data(),
            'last_page' => $this->lastPage(),
            'current_page' => $this->currentPage(),
            'total' => $this->total(),
            'path' => $this->path(),
            'empty' => $this->isEmpty(),
            'from' => $this->firstItem(),
            'last_page_url' => $this->url($this->lastPage()),
            'next_page_url' => $this->nextPageUrl(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
        ];
    }

    /**
     * Get the resource collection data as array
     *
     * @return array
     */
    protected function data()
    {
        return $this->class::collection($this->collection)
            ->toArray(request());
    }
}
