<?php

namespace App\Http\Controllers\Api;

use App\Client;
use Illuminate\Http\Request;
use App\Http\Paginate\Paginate;
use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\ClientTransformer;

class ClientController extends ApiController
{
    /**
     * Client Controller constructor.
     *
     * @param ClientTransformer $transformer
     */
    public function __construct(ClientTransformer $transformer, Client $client)
    {
        $this->transformer = $transformer;
        $this->client = $client;
    }

    /**
     * Index function
     *
     * @return Response
     */
    public function index()
    {
        $clients = new Paginate($this->client->query());
        return $this->responseWithPagination($clients);
    }
}
