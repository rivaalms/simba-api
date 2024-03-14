<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
   /**
    * Register any application services.
    */
   public function register(): void
   {
      //
   }

   /**
    * Bootstrap any application services.
    */
   public function boot(): void
   {
      Relation::enforceMorphMap([
         'school' => "App\Models\School",
         'supervisor' => "App\Models\Supervisor",
         'officer' => "App\Models\Officer",
         'user' => "App\Models\User"
      ]);
   }
}
