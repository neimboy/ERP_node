<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Asignacion;
use App\Models\Proyecto;
use App\Models\Orden;

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
        Route::model('asignacione', Asignacion::class);
        Route::model('proyecto', Proyecto::class);
        Route::model('ordene', \App\Models\Orden::class);
    }
}
