<?php

namespace App\Exceptions;

use App\Enums\StatusEnum;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'error' => 'Token has expired.',
                'status' => StatusEnum::DECLINED
            ], 401);
        }

        if ($exception instanceof FatalError) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => StatusEnum::DECLINED
            ], 401);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'error' => $exception->getMessage(),
                'status' => StatusEnum::DECLINED
            ], 403);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'error' => 'Too many attempts.',
                'status' => StatusEnum::DECLINED
            ], 429);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'The requested resource was not found.',
                'status' => StatusEnum::DECLINED
            ], 404);
        }

        return parent::render($request, $exception);
    }
}
