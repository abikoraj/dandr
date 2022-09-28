<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $exception)
    {
        if (!config('app.debug')) {

            if ($request->wantsJson()) {

                /*  is this exception? */

                if (!empty($exception)) {

                    // set default error message
                    $response = [
                        'error' => 'Sorry, can not execute your request.'
                    ];

                    // If debug mode is enabled
                    // Add the exception class name, message and stack trace to response
                    $response['message'] = $exception->getMessage();

                    $status = 400;

                    // get correct status code

                    // is this validation exception
                    if ($exception instanceof ValidationException) {

                        return $this->convertValidationExceptionToResponse($exception, $request);

                        // is it authentication exception
                    } else if ($exception instanceof AuthenticationException) {

                        $status = 401;

                        $response['error'] = 'Can not finish authentication!';

                        //is it DB exception
                    } else if ($exception instanceof \PDOException) {

                        $status = 500;

                        $response['error'] = 'Can not finish your query request!';

                        // is it http exception (this can give us status code)
                    } else if ($this->isHttpException($exception)) {

                        $status = $exception->getStatusCode();

                        $response['error'] = 'Request error!';
                    } else {

                        // for all others check do we have method getStatusCode and try to get it
                        // otherwise, set the status to 400
                        $status = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 400;
                    }

                    return response()->json($response, $status);
                }
            }
        }

        return parent::render($request, $exception);

    }
}
