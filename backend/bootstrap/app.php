<?php

use App\Helpers\JsonResponse;
use App\Helpers\ResponseStatus;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (MethodNotAllowedHttpException $exception, $request) {
            return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_ALLOWED), ResponseStatus::NOT_ALLOWED);
        });
        $exceptions->renderable(function (NotFoundHttpException $exception, $request) {
            return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_FOUND), ResponseStatus::NOT_FOUND);
        });
        $exceptions->renderable(function (ModelNotFoundException $exception, $request) {
            return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_FOUND), ResponseStatus::NOT_FOUND);
        });
        $exceptions->renderable(function (AccessDeniedHttpException $exception, $request) {
            return JsonResponse::respondError(trans(JsonResponse::MSG_NOT_AUTHORIZED), ResponseStatus::ACCESS_FORBIDDEN);
        });

    })->create();
