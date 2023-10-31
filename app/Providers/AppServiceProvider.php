<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

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
