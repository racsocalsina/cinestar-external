<?php

namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

abstract class ApiResponse
{
    public static function success($data, $message = null)
    {
        $status = Response::HTTP_OK;

        return response()->json(compact('status', 'data', 'message'), $status);
    }

    public static function noContent()
    {
        return response()->json(Response::HTTP_NO_CONTENT);
    }

    public static function unprocessable($errors)
    {
        $status = Response::HTTP_UNPROCESSABLE_ENTITY;
        $message = $errors->first();

        return response()->json(compact('status','message', 'errors'), $status);
    }
    public static function failed($message, $errors = [])
    {
        $status = Response::HTTP_UNPROCESSABLE_ENTITY;

        return response()->json(compact('status','message', 'errors'), $status);
    }

    public static function unauthorized($message = null)
    {
        $status = Response::HTTP_UNAUTHORIZED;
        $message = $message ?? __('Unauthorized');

        return response()->json(compact('status', 'message'), $status);
    }

    public static function internalServerError($data = null, $message = null){
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = $message ?? __('http.internal_server_error');

        return response()->json(compact('status', 'message', 'data'), $status);
    }

    public static function forbidden($data = null, $message = null){
        $status = Response::HTTP_FORBIDDEN;
        $message = $message ?? __('http.forbidden');

        return response()->json(compact('status', 'message', 'data'), $status);
    }

    public static function response($data = null, $message = null, $status = null)
    {
        $status = $status?:Response::HTTP_OK;

        return response()->json(compact('status', 'message', 'data'), $status);
    }

    public static  function exception(\Exception $e, $message = null, $data = [])
    {
        $message = !is_null($message) ? $message : __('Bad request');
        $exception = [
            'status' => Response::HTTP_BAD_REQUEST,
            'message' => $message
        ];

        if (env("APP_DEBUG")) {
            $exception['dev'] = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'exception' => $e->getMessage(),

            ];
        }
        $data = array_merge($data, $exception);

        return response()->json($data, Response::HTTP_BAD_REQUEST);
    }
    public static  function create($data = [])
    {


        return response()->json([
            'status'=>Response::HTTP_CREATED,
            'data'=>$data
        ], Response::HTTP_CREATED);
    }

    public static  function excel($import, $name = null)
    {

        $name = self::getFileName($name, $import);
        return Excel::download($import, $name);
    }

    public static  function paginate( $collection)
    {
        return $collection->additional(['status'=>Response::HTTP_OK]);

    }

    /**
     * @param $name
     * @param $import
     * @return mixed|string
     */
    private static function getFileName($name, $import)
    {
        $name =  $name ? $name : (new \ReflectionClass($import))->getShortName();
        $name = Str::slug($name."_".Carbon::now()->format('Y_m_d_h_i_s'));
        return $name . ".xlsx";
    }
}
