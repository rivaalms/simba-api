<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
      web: __DIR__ . '/../routes/web.php',
      commands: __DIR__ . '/../routes/console.php',
      health: '/up',
      api: __DIR__ . '/../routes/api.php',
      apiPrefix: 'api',
   )
   ->withMiddleware(function (Middleware $middleware) {
      $middleware->statefulApi();
   })
   ->withExceptions(function (Exceptions $exceptions) {
      $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
         if ($request->is('api/*')) {
            return response()->json([
               'success' => false,
               'message' => 'Unauthenticated',
               'data' => null
            ]);
         }
      });
   })->create();
