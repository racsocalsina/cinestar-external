<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use BadMethodCallException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof BadMethodCallException
                || $exception instanceof  MethodNotAllowedHttpException
                || $exception instanceof NotFoundHttpException
            ) {
                return $this->errorResponse(['message' => __('app.error.not_found'), 'dev'=>$exception->getMessage() ], Response::HTTP_INTERNAL_SERVER_ERROR, $exception);
            }
            if ($exception instanceof ValidationException) {
                $message = "";
                foreach ($exception->validator->messages()->all() as $item => $value) {
                    $message .= $message == "" ? $value : " $value";
                }
                return $this->errorResponse(['message' => __('app.error.unprocessable_entity'), 'dev' => $message], Response::HTTP_UNPROCESSABLE_ENTITY, $exception);
            }
            if ($exception instanceof AuthenticationException) {
                return $this->errorResponse(['message' => __('app.error.unauthorized')], Response::HTTP_UNAUTHORIZED, $exception);
            }
            if ($exception instanceof AuthorizationException) {
                return $this->errorResponse(['message' => __('app.error.forbidden')], Response::HTTP_FORBIDDEN, $exception);
            }
        }
        return parent::render($request, $exception);
    }
}
