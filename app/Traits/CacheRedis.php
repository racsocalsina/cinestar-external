<?php

namespace App\Traits;

use App\Horizon\Auth\Users\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Trait CacheRedis
 *
 * @package App\Traits
 */
trait CacheRedis
{

    /**
     * Extract the key saved in redis
     *
     * @param string $key      name of the key
     * @param string $userId   id of user
     * @param        $company
     * @param string $database name of the database redis
     * @return mixed
     */
    protected function getCache($key, $userId = '', $company = '', $database = '')
    {
        $keyCustoms = $this->generateKeyCacheAuth($key, $userId, $company);

        if (!empty($database)) {
            $response = Cache::store($database)->get($keyCustoms);
        } else {
            $response = Cache::get($keyCustoms);
        }

        return $response;
    }

    /**
     * Save or update the key in redis
     *
     * @param string $key      name of the key
     * @param mixed  $values   the object to be saved
     * @param int    $time     time in session
     * @param string $userId   id of user
     * @param        $company
     * @param string $database name of the database redis
     */
    protected function cache($key, $values, $time, $userId = '', $company = '', $database = ''): void
    {
        $keyCustoms = $this->generateKeyCacheAuth($key, $userId, $company);

        if (!empty($database)) {
            Cache::store($database)->put($keyCustoms, $values, $time);
        } else {
            Cache::put($keyCustoms, $values, $time);
        }
    }

    /**
     * Validate if your key exists in redis
     *
     * @param string $key      name of the key
     * @param string $userId   id of user
     * @param        $company
     * @param string $database name of the database redis
     * @return bool
     */
    protected function hasCache($key, $userId = '', $company = '', $database = ''): bool
    {
        $keyCustoms = $this->generateKeyCacheAuth($key, $userId, $company);
        if (!empty($database)) {
            if (!empty(Cache::store($database)->has($keyCustoms))) {
                $status = true;
            } else {
                $status = false;
            }
        } elseif (empty(Cache::has($keyCustoms))) {
            $status = false;
        } else {
            $status = true;
        }

        return $status;
    }

    /**
     * Delete the key saved in redis
     *
     * @param string $key      name of the key
     * @param string $userId   id of user
     * @param        $company
     * @param string $database name of the database redis
     * @return void
     */
    protected function forgetCache($key, $userId = '', $company = '', $database = ''): void
    {
        $keyCustoms = $this->generateKeyCacheAuth($key, $userId, $company);

        if (!empty($database)) {
            Cache::store($database)->forget($keyCustoms);
        } else {
            Cache::forget($keyCustoms);
        }
    }

    /**
     * Initiating the progress in 1%
     *
     * @param $name
     * @param $time
     * @param $user
     * @param $company
     */
    protected function cacheProgressStarting($name, $time, $user, $company): void
    {
        $response = ['percentage' => 1, 'status' => 'Starting', 'success' => true];
        $this->cache($name, $response, $time, $user, $company);
    }

    /**
     * Process progress in redis
     *
     * @param       $name
     * @param       $time
     * @param       $user
     * @param       $company
     * @param       $progress
     * @param array $params
     */
    protected function cacheProgressProcessing($name, $time, $user, $company, $progress, $params = []): void
    {
        if (!empty($params)) {
            $response = array_merge(['percentage' => ceil($progress), 'status' => 'Processing', 'success' => true],
                $params);
        } else {
            $response = ['percentage' => ceil($progress), 'status' => 'Processing', 'success' => true];
        }

        $this->cache($name, $response, $time, $user, $company);
    }

    /**
     * Complete process in 100%
     *
     * @param       $name
     * @param       $time
     * @param       $user
     * @param       $company
     * @param array $params
     * @return void
     */
    protected function cacheProgressComplete($name, $time, $user, $company, $params = []): void
    {
        try {
            if (!empty($params)) {
                $response = array_merge(['percentage' => 100, 'status' => 'Complete', 'success' => true], $params);
            } else {
                $response = ['percentage' => 100, 'status' => 'Complete', 'success' => true];
            }

            $this->cache($name, $response, $time, $user, $company);
        } catch (\Exception $exception) {
            logger($exception);
        }

    }

    /**
     * Execute a failed process
     *
     * @param       $name
     * @param       $time
     * @param       $user
     * @param       $company
     * @param array $params
     */
    protected function cacheProgressFails($name, $time, $user, $company, $params = [], $exception = null): void
    {
        logger($exception);

        if (!empty($params)) {
            $response = array_merge(['percentage' => 0, 'status' => 'Fails', 'success' => false], $params);
        } else {
            $response = ['percentage' => 0, 'status' => 'Fails', 'success' => false];
        }
        if(isset($exception)){
            logger($exception);
        }

        try{
            $this->cache($name, $response, $time, $user, $company);
        }catch (\Exception $exception2) {
            logger($exception2);
        }


    }

    /**
     * Delete the progress after complete
     *
     * @param $name
     * @param $user
     * @param $company
     */
    protected function cacheProgressForget($name, $user, $company): void
    {
        $this->forgetCache($name, $user, $company);
    }

    /**
     * Returns the status of the process
     *
     * @param       $identify
     * @param       $user
     * @param       $company
     * @param array $params
     * @return JsonResponse
     */
    protected function statusProgressJobs($identify, $user, $company, $params = []): JsonResponse
    {
        $responseCache = $this->getCache($identify, $user, $company);
        if ($responseCache !== null) {
            $data = $this->responseValue($responseCache, $params);
            switch ($data) {
                case ((((int)$data['percentage'] < 100) && ((int)$data['percentage'] > 0)) && (boolean)$data['success'] === true):
                    $response = $this->successResponse([$data], 206);
                    $flushCache = false;
                    break;
                case ((int)$data['percentage'] === 100 && (boolean)$data['success'] === true):
                    if ($params != []) {
                        if ($params['type'] == 'export') {
                            $data['message'] = 'Descarga exitosa.';
                        } else if ($params['type'] == 'import') {
                            $data['message'] = 'Importación exitosa.';
                        } else {
                            $data['message'] = 'Se habilitaron los registros correctamente';
                        }
                    }
                    $response = $this->successResponse([$data]);
                    $flushCache = true;
                    break;
                case ((int)$data['percentage'] === 0 && (boolean)$data['success'] === false):
                    $response = $this->successResponse([$data], 404);
                    $flushCache = true;
                    break;
                default:
                    $response = $this->successResponse([$data], 400);
                    $flushCache = true;
                    break;
            }

            if ($flushCache) {
                /** elimina todos las respuestas despues del 100% **/
                $this->forgetCache($identify, $user, $company);
            }
        } else {
            $response = $this->errorResponse(['status' => 'No data', 'success' => false], 400);
        }

        return $response;
    }

    /**
     * valid information if you need a parameter
     *
     * @param $response
     * @param $params
     * @return array
     */
    private function responseValue($response, $params): array
    {
        if (empty($params)) {
            $responseData = $response;
        } else {
            $responseData = array_merge($response, $params);
        }

        return $responseData;
    }

    /**
     * Generate identify dynamic in the redis
     *
     * @param        $key
     * @param string $userId
     * @param string $company
     * @return string
     */
    private function generateKeyCacheAuth($key, $userId = '', $company = ''): string
    {
        return $key . '-' . md5($userId . ' - ' . $company);
    }
}
