<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$trait = new class {
    use \App\Traits\ApiResponse;
};

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) use ($trait) {
        $exceptions->render(function (NotFoundHttpException $exception) use ($trait) {
            return $trait->sendError('Error');
        });
        $exceptions->render(function (AccessDeniedHttpException $exception) use ($trait) {
            return $trait->sendError($exception->getMessage() ?: 'Access Denied');
        });
    })->create();
