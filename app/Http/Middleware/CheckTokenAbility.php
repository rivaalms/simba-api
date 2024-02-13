<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class CheckTokenAbility
{
   /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param mixed ...$abilities
    * @return \Illuminate\Http\Response
    */
   public function handle(Request $request, Closure $next, ...$abilities)
   {
      if (! $request->user() || ! $request->user()->currentAccessToken()) {
         throw new AuthenticationException;
      }

      foreach ($abilities as $ability) {
         if ($request->user()->tokenCan($ability)) {
            return $next($request);
         }
      }

      $controller = new \App\Http\Controllers\Controller;

      return $controller->apiResponse($controller->jsonify((new MissingAbilityException($abilities))->getMessage()), 'Akses ditolak', 403);
   }
}
