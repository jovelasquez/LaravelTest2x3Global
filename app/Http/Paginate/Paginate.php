<?php

namespace App\Http\Paginate;

use Illuminate\Database\Eloquent\Builder;

class Paginate
{
    /**
     * Total count of the items.
     *
     * @var int
     */
    protected $total;

    /**
     * Collection of items.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $data;
    protected $limit;
    protected $page;

    /**
     * Paginate constructor.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param int $limit
     * @param int $page
     */
    public function __construct(Builder $builder, $limit = 20, $page = 1)
    {
        $this->limit = request()->get('limit', $limit);
        $this->page = request()->get('page', $page);
        $this->total = $builder->count();
        $this->data = $builder
            ->offset(($this->page - 1) * $this->limit)
            ->limit($this->limit)
            ->get();
    }

    /**
     * Get the paginated collection of items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the total count of the items.
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Get the total pages.
     *
     * @return int
     */
    public function getTotalPages()
    {
        return ceil($this->total / $this->limit);
    }

    /**
     * Get the total pages.
     *
     * @return int
     */
    public function getTotalPerPages()
    {
        return (int) $this->limit;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return (int) $this->page;
    }

    /**
     *
     *
     * @return int
     */
    public function getNextPage()
    {
        return $this->page + 1 <= $this->getTotalPages()
                ? (int) $this->page + 1
                : null;
    }

    /**
     *
     *
     * @return int
     */
    public function getPreviousPage()
    {
        return $this->page - 1 > 1
                ? (int) $this->page - 1
                : null;
    }
}
