<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Event;
use App\Events\OrdenEjecutada;
use App\Listeners\ActualizarCotizacionPorOrdenEjecutada;
use App\Models\Asignacion;
use App\Models\Proyecto;
use App\Repositories\Ventas\VentaRepositoryInterface;
use App\Repositories\Ventas\VentaRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(VentaRepositoryInterface::class, VentaRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::model('asignacione', Asignacion::class);
        Route::model('proyecto', Proyecto::class);

        // Registrar listener dinámicamente por compatibilidad si no se está usando EventServiceProvider de app
        Event::listen(OrdenEjecutada::class, [ActualizarCotizacionPorOrdenEjecutada::class, 'handle']);
    }
}
