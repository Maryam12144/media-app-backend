<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Modules\Core\Libraries\QueryFilter;

/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   @SWG\Info(
 *     title="LV-Core",
 *     version="2.0",
 *     description="247labs Laravel & Vuejs Core",
 *     @SWG\Contact(
 *         email="muhammad_usman@usa.edu.pk"
 *     )
 *   )
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return forbidden 403 error
     *
     * @return ResponseFactory|Response
     */
    protected function forbidden()
    {
        return response([
            'message' => __('general.forbidden')
        ], 403);
    }

    /**
     * Return not found 404 error
     *
     * @return ResponseFactory|Response
     */
    protected function notFound()
    {
        return response([
            'message' => __('general.not-found')
        ], 404);
    }

    /**
     * Return validation json response
     *
     * @param Validator $validator
     * @param bool $json
     * @return ResponseFactory|Response
     * @throws ValidationException
     */
    protected function validationError($validator, $json = true)
    {
        if (!$json) throw ValidationException::withMessages(
            $validator->errors()->all()
        );

        return response([
            'errors' => $validator->errors()
        ], 422);
    }

    /**
     * Return success response to user
     *
     * @param string|null $message
     * @param array|null $data
     * @return ResponseFactory|Response
     */
    protected function successResponse($message = null, $data = null)
    {
        $response = [
            'message' => $message ?: __('general.success')
        ];

        if ($data) $response['data'] = $data;

        return response($response, 200);
    }

    /**
     * Return data response
     *
     * @param array $data
     * @return ResponseFactory|Response
     */
    protected function dataResponse($data)
    {
        return response([
            'message' => __('general.success'),
            'data' => $data
        ], 200);
    }

    /**
     * Return error response to user
     *
     * @param string|null $message
     * @param array|null $data
     * @param int $status
     * @return ResponseFactory|Response
     */
    protected function errorResponse($message = null, $data = null, $status = 422)
    {
        $response = [
            'message' => $message ?: __('general.error')
        ];

        if ($data) $response['data'] = $data;

        return response($response, $status);
    }

    /**
     * Paginate the filtered items
     *
     * @param Request $request
     * @param Builder|Relation $query
     * @return LengthAwarePaginator
     */
    protected function paginateFiltered($request, $query)
    {
        return $query->paginate(
            QueryFilter::paginateItemsCount(
                $request->get('items')
                    ?: $request->get('limit')
            )
        );
    }

    /**
     * Fetch the results of the query without
     * pagination but limit the number of the
     * results according to the "items" param if
     * any is passed, else to the default limit set
     *
     * @param Request $request
     * @param Builder|HasMany $query
     * @return Builder[]|Collection
     */
    protected function fetchWithoutPagination($request, $query)
    {
        return $query
            ->limit(QueryFilter::paginateItemsCount(
                $request->get('items')
            ))
            ->get();
    }
}
