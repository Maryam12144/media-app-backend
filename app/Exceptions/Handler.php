<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{

     /**
     * Error reason constants
     */
    const ERROR_REASON_AUTHENTICATION = 'authentication';
    const ERROR_REASON_AUTHORIZATION = 'authorization';
    const ERROR_REASON_BANNED = 'banned';

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $exception
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException
            && $request->expectsJson()) { 
            return response([
                'message' => __('general.forbidden'),
                'reason' => self::ERROR_REASON_AUTHENTICATION
            ], 403);
        }

       // if ($exception instanceof ValidationException) {
       //     return response([
       //         'errors' => $exception->errorBag->errors()
       //     ], 422);
       // }

        if ($exception instanceof NotFoundHttpException && $request->expectsJson()) {
            return response([
                'message' => __('general.not-found')
            ], 404);
        }

        if ($exception instanceof ModelNotFoundException && $request->expectsJson()) {
            return response([
                'message' => __('general.not-found')
            ], 404);
        }

        if ($exception instanceof AuthorizationException && $request->expectsJson()) {
            return response([
                'message' => __('general.forbidden'),
                'reason' => self::ERROR_REASON_AUTHORIZATION
            ], 403);
        }

        if ($exception instanceof HttpException
            && $exception->getStatusCode() == 403
            && $request->expectsJson()) {
            return response([
                'message' => __('general.forbidden')
            ], 403);
        }

        // if(auth()->check() == false){
        //     return response([
        //         'message' => __('general.forbidden'),
        //         'reason' => self::ERROR_REASON_AUTHENTICATION
        //     ], 403);
        // }
        
        return parent::render($request, $exception);

        
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
