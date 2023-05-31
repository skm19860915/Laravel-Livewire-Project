<?php

namespace App\Providers;

use App\Models\Location;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //

  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {

    Blade::if('isSuperadmin', function ($role) {
      return $role == 1;
    });

    Blade::if('isAdmin', function ($role) {
      return $role == 1 || $role == 2;
    });

    view()->composer('*', function ($view) {

      if (auth()->check()) {
        $auth = auth()->user();
        // get current locations (primary location)
        $current = $auth->currentlocation->first();
        if (!session('current_location')) {
          if ($current) {
            session(['current_location' => $current]);
          }
        }
        // if super admin get all locations
        if (auth()->user()->role->id == 1) {
          View::share('locations',   Location::getAll());
        } else {
          // if not super admin show his locations
          View::share('locations', $auth->locations);
        }
      }
    });
  }
}
