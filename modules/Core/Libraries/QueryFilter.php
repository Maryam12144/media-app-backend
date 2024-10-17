<?php

namespace Modules\Core\Libraries;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    /**
     * Allowed pagination items count
     */
    const PAGINATION_ALLOWED_ITEMS = [
        5, 10, 15, 20, 25
    ];

    /**
     * Default items count for pagination
     */
    const DEFAULT_PAGINATION_ITEMS = 10;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $query;

    /**
     * QueryFilter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply($builder)
    {
        $this->query = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = Str::camel($field);

            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], (array)$value);
            }
        }

        return $this->query;
    }

    /**
     * Get the filter fields
     *
     * @return array
     */
    protected function fields()
    {
        return array_filter(
            array_map('trim', $this->request->all())
        );
    }

    /**
     * Clear previous order by clauses
     *
     * @return void
     */
    protected function clearOrder()
    {
        $this->query->getQuery()->orders = null;
    }

    /**
     * Get the paginated data count
     *
     * @param int $count
     * @return int
     */
    public static function paginateItemsCount($count = null)
    {
        $validCount = $count &&
            is_numeric($count) &&
            in_array($count, self::PAGINATION_ALLOWED_ITEMS);

        return $validCount ? $count : self::DEFAULT_PAGINATION_ITEMS;
    }
}
