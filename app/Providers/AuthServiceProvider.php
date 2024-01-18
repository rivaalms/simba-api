<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
   /**
    * The model to policy mappings for the application.
    *
    * @var array<class-string, class-string>
    */
   protected $policies = [
      //
   ];

   /**
    * Register any authentication / authorization services.
    */
   public function boot(): void
   {
      ResetPassword::createUrlUsing(function (User $user, string $token) {
         $endpoint = null;
         switch ($user->userable_type) {
            default:
               $endpoint = env('CLIENT_ADMIN_URL');
               break;
         }

         return "{$endpoint}/reset-password?token={$token}&email={$user->email}";
      });
   }
}
