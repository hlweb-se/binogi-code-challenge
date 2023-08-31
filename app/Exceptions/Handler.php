<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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

        // Add our own response for errors we want to handle by ourself.
        // Only change the response for api requests that expects a json response (otherwise show the standard 404-page for browsers, etc).
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') && $request->wantsJson()) {
                return response()->json([
                    'message' => 'User not found. Check the URL and/or user id.'
                ], 404);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
