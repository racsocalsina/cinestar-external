<?php

namespace App\Traits;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    protected function success($data = [])
    {
        return response()->json(['status' => Response::HTTP_OK, 'data' => $data], Response::HTTP_OK);
    }

    protected function created($data = [])
    {
        return response()->json(['status' => Response::HTTP_CREATED, 'data' => $data], Response::HTTP_CREATED);
    }

    protected function successResponse($data, $code = 200)
    {
        return response()->json([
            'status' => $code,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($error, $code = 401, $exception = null)
    {
        if(isset($exception)){
            logger($exception);
        }

        $error['status'] = $code;

        return response()->json($error, $code);
    }

    protected function internalErrorResponse($exception)
    {
        if ($exception instanceof ClientException)
        {
            $errorMessage = $exception->getMessage();
        } else if ($exception instanceof ServerException) {
            $errorMessage = $exception->getMessage();
        } else {
            $errorMessage = $exception->getMessage();
        }

        return response()->json([
            'message' => $errorMessage,
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'code' => $exception->getCode(),
            'trace' => $exception->getTraceAsString(),
        ], 500);
    }

    /**
     * @param Collection $collection
     * @param int        $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function showAll($collection, $transformer = null, $code = 200)
    {
        if (empty($collection) || is_array($collection)) {
            return $this->successResponse(['data' => $collection], $code);
        }

        if ($transformer) {
            $collection = $this->transformData($collection, $transformer);
            return $this->successResponse($collection, $code);
        }

        $collection = $this->cacheResponse($collection);

        switch (get_class($collection)) {
            case 'Illuminate\Http\Resources\Json\AnonymousResourceCollection':
                //Laravel Resource
                return $collection->response()->setStatusCode($code);
                break;
            case 'Illuminate\Support\Collection':
                //Laravel Colletion
                return $this->successResponse($collection, $code);
                break;
            default:
                return $this->successResponse($collection, $code);
                break;
        }
    }

    protected function showOne($instance, $code = 200)
    {
        if ($instance instanceof JsonResource) {
            return $instance->response()->setStatusCode($code);
        }

        return $this->successResponse($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => ['message' => $message, 'code' => $code]], $code);
        //msj
    }

    protected function filterData(Collection $collection)
    {
        foreach (request()->query() as $query => $value) {
            if (isset($value)) {
                $collection = $collection->where($query, $value);
            }
        }

        return $collection;
    }

    protected function sortData(Collection $collection)
    {
        if (request()->has('sort_by')) {
            $collection = $collection->sortBy->{request()->sort_by};
        }

        return $collection;
    }

    /**
     * @param Collection $collection
     * @return LengthAwarePaginator
     */
    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:500'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all());

        return $paginated;
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    protected function cacheResponse($data)
    {
        $useCache = request()->get('cache') === 'true';

        $url = request()->url();
        //Remove cache key from params
        $queryParams = array_diff_key(request()->query(), array_flip(array('cache')));

        ksort($queryParams, SORT_STRING);

        $queryString = http_build_query($queryParams);

        $fullUrl = "{$url}?{$queryString}";

        if ($useCache) {
            return Cache::remember($fullUrl, 30 / 60, function () use ($data) {
                return $data;
            });
        } else {
            Cache::put($fullUrl, $data, 30 / 60);
            return $data;
        }
    }

    protected function unauthorized($message = null)
    {
        $message = $message ? $message : __('auth.failed');

        return response()->json([
            "status" => 401,
            "message" => $message
        ], Response::HTTP_UNAUTHORIZED);
    }

    protected function forbidden($message = null)
    {
        $message = $message ? $message : __('auth.forbidden');

        return response()->json([
            "status" => 403,
            "message" => $message
        ], Response::HTTP_FORBIDDEN);
    }

    public function responseMessageFail($message = null, $data = [], $status = 422, $dev = null)
    {
        $message = !is_null($message) ? $message : __('app.commons.failed');

        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];

        if ($dev) {
            $response['dev'] = $dev;
        }

        return response()->json($response, $status);
    }
}
