<?php

namespace App\Exceptions;

use Throwable;
use Sentry\Laravel\Integration;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
            Integration::captureUnhandledException($e);
        });
    }
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            Log::channel('security')->error($exception->getMessage(), [
                'exception' => $exception,
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
                'user_agent' => request()->userAgent(),
                'user_id' => auth()->id(),
            ]);
        }
    
        parent::report($exception);
    }
}
