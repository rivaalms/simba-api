<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenAbility
{
   /**
    * Handle an incoming request.
    *
    * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    */
   public function handle(Request $request, Closure $next, ...$abilities): Response
   {
      if (!$request->user() || !$request->user()->currentAccessToken()) {
         throw new AuthenticationException;
      }

      foreach ($abilities as $ability) {
         if ($request->user()->tokenCan($ability)) {
            return $next($request);
         }
      }

      return response()->json([
         'success' => false,
         'message' => 'Akses tidak diizinkan',
         'data' => (new MissingAbilityException($abilities))->getMessage()
      ], 403);
   }
}
