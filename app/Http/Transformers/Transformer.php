<?php

namespace App\Http\Transformers;

use Illuminate\Support\Collection;
use App\Http\Paginate\Paginate;

abstract class Transformer
{
    /**
     * Resource name of the json object.
     *
     * @var string
     */
    protected $resourceName = 'payload';

    /**
     * Transform a collection of items.
     *
     * @param Collection $payload
     * @return array
     */
    public function collection(Collection $payload)
    {
        return [
            'success' => true,
            'payload' => $payload->map([$this, 'transform'])
        ];
    }

    /**
     * Transform a single item.
     *
     * @param $payload
     * @return array
     */
    public function item($payload)
    {
        return [
            'payload' => $this->transform($payload)
        ];
    }

    /**
     * Transform a paginated item.
     *
     * @param Paginate $paginated
     * @return array
     */
    public function paginate(Paginate $paginated)
    {
        return [
            'payload' => $paginated->getData()->map([$this, 'transform']),
            'pagination' => [
                'totalRows' => $paginated->getTotal(),
                'rowsPerPage' => $paginated->getTotalPerPages(),
                'totalPages' => $paginated->getTotalPages(),
                'currentPage' => $paginated->getCurrentPage(),
                'nextPage' => $paginated->getNextPage(),
                'previousPage' => $paginated->getPreviousPage(),
            ]
        ];
    }

    /**
     * Apply the transformation.
     *
     * @param $payload
     * @return mixed
     */
    abstract public function transform($payload);
}
