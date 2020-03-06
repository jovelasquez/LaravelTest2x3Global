<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Http\Paginate\Paginate;
use App\Http\Transformers\Transformer;

class ApiController extends Controller
{
    /**
     * \App\Http\Transformers\Transformer
     *
     * @var null
     */
    protected $transformer = null;

    /**
     * Return generic json response with the given payload.
     *
     * @param array $payload
     * @param int   $statusCode
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($payload, $statusCode = 200, $headers = [])
    {
        return response()->json($payload, $statusCode, $headers);
    }

    /**
     * response with payload after applying transformer.
     *
     * @param $payload
     * @param int $statusCode
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithTransformer($payload, $statusCode = 200, $headers = [])
    {
        $this->checkTransformer();

        if ($payload instanceof Collection) {
            $payload = $this->transformer->collection($payload);
        } else {
            $payload = $this->transformer->item($payload);
        }
        
        return $this->response($payload, $statusCode, $headers);
    }

    /**
     * response with pagination.
     *
     * @param $paginated
     * @param int $statusCode
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseWithPagination($paginated, $statusCode = 200, $headers = [])
    {
        $this->checkPaginated($paginated);

        $this->checkTransformer();

        $payload = $this->transformer->paginate($paginated);

        return $this->response($payload, $statusCode, $headers);
    }

    /**
     * response with success.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccessMessage($message, $payload = [], $statusCode = 200, $headers = [])
    {
        $this->checkTransformer();

        $response = array_merge(
            ["message" => $message],
            $this->transformer->item($payload)
        );
        
        return $this->response($response, $statusCode = 200, $headers = []);
    }

    /**
     * response with created.
     *
     * @param $payload
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseCreated($payload)
    {
        return $this->response($payload, 201);
    }

    /**
     * response with no content.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseNoContent()
    {
        return $this->response(null, 204);
    }

    /**
     * response with error.
     *
     * @param $message
     * @param $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseError($message, $statusCode)
    {
        $response = [
            'message' => $message,
            'payload' => []
        ];
        return $this->response($response, $statusCode);
    }

    /**
     * response with unauthorized.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseUnauthorized($message = 'Unauthorized')
    {
        return $this->responseError($message, 401);
    }

    /**
     * response with forbidden.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseForbidden($message = 'Forbidden')
    {
        return $this->responseError($message, 403);
    }

    /**
     * response with not found.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseNotFound($message = 'Not Found')
    {
        $response = [
            'success' => false,
            'message' => $message,
            'payload' => []
        ];
        return $this->responseError($message, 404);
    }

    /**
     * response with internal error.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseInternalError($message = 'Internal Error')
    {
        return $this->responseError($message, 500);
    }

    /**
     * Check if valid transformer is set.
     *
     * @throws Exception
     */
    private function checkTransformer()
    {
        if ($this->transformer === null || !$this->transformer instanceof Transformer) {
            throw new Exception('Invalid data transformer.');
        }
    }

    /**
     * Check if valid paginate instance.
     *
     * @param $paginated
     * @throws Exception
     */
    private function checkPaginated($paginated)
    {
        if (!$paginated instanceof Paginate) {
            throw new Exception('Expected instance of Paginate.');
        }
    }
}
