<?php

namespace App\Services;

use App\Models\Asiento;
use App\Models\AsientoDetalle;
use Illuminate\Support\Facades\DB;

class ContabilidadService
{
    public static function crearAsiento($periodo, $fecha, $glosa, $detalles)
    {
        return DB::transaction(function () use ($periodo, $fecha, $glosa, $detalles) {

            $totalDebe = collect($detalles)->sum('Debe');
            $totalHaber = collect($detalles)->sum('Haber');

            // 🔥 Validación contable REAL
            if ($totalDebe != $totalHaber) {
                throw new \Exception('Asiento no balanceado');
            }

            $asiento = Asiento::create([
                'Id_Periodo' => $periodo,
                'Fecha' => $fecha,
                'Glosa' => $glosa,
            ]);

            foreach ($detalles as $detalle) {
                AsientoDetalle::create([
                    'Id_Asiento' => $asiento->Id_Asiento,
                    'Id_Cuenta' => $detalle['Id_Cuenta'],
                    'Debe' => $detalle['Debe'],
                    'Haber' => $detalle['Haber'],
                ]);
            }

            return $asiento;
        });
    }
}