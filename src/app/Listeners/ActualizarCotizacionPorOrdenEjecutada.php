<?php

namespace App\Listeners;

use App\Events\OrdenEjecutada;
use App\Models\Cotizacion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ActualizarCotizacionPorOrdenEjecutada
{
    /**
     * Handle the event.
     */
    public function handle(OrdenEjecutada $event): void
    {
        try {
            $orden = $event->orden;
            $idCot = $orden->Id_Cotizacion ?? null;
            if (!$idCot) return;

            $cot = Cotizacion::where('Id_Cotizacion', $idCot)->first();
            if (!$cot) return;

            $col = Schema::hasColumn($cot->getTable(), 'Estado') ? 'Estado' : 'estado';
            // Al ejecutar la orden, marcamos la cotización como CERRADA para reflejar finalización
            $cot->{$col} = 'CERRADA';
            $cot->save();

            Log::info("Cotización {$cot->Id_Cotizacion} marcada como CERRADA por orden {$orden->Id_Orden}");
        } catch (\Exception $e) {
            Log::error('Error en listener ActualizarCotizacionPorOrdenEjecutada: ' . $e->getMessage());
        }
    }
}
