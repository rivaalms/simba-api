<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AuthServiceProvider extends ServiceProvider
{
   /**
    * Register services.
    */
   public function register(): void
   {
      //
   }

   /**
    * Bootstrap services.
    */
   public function boot(): void
   {
      ResetPassword::createUrlUsing(function (User $user, string $token) {
         $userType = strtoupper($user->userable_type);
         $endpoint = env("CLIENT_{$userType}_URL");

         return "{$endpoint}/reset-password?token={$token}&email={$user->email}";
      });
   }
}
