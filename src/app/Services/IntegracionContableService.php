<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Pago;
use App\Models\Periodo;
use App\Models\CuentaContable;
use Illuminate\Support\Facades\DB;

class IntegracionContableService
{
    /**
     * Genera asiento contable para una factura de venta
     */
    public static function registrarFactura(Factura $factura)
    {
        return DB::transaction(function () use ($factura) {
            // Obtener el periodo activo según la fecha de la factura
            $periodo = Periodo::where('Año', $factura->Fecha->year)
                              ->where('Mes', $factura->Fecha->month)
                              ->first();

            if (!$periodo) {
                throw new \Exception("Periodo contable no encontrado para {$factura->Fecha->month}/{$factura->Fecha->year}");
            }

            // Buscar cuentas contables (debes tenerlas creadas en tu plan de cuentas)
            $cuentaClientes = CuentaContable::where('Codigo', '1212')->first(); // Cuentas por cobrar
            $cuentaVentas = CuentaContable::where('Codigo', '7011')->first();    // Ventas
            $cuentaIGV = CuentaContable::where('Codigo', '4011')->first();        // IGV por pagar

            if (!$cuentaClientes || !$cuentaVentas || !$cuentaIGV) {
                throw new \Exception("Faltan cuentas contables configuradas");
            }

            $totalSinIGV = $factura->Total / 1.18;
            $igv = $factura->Total - $totalSinIGV;

            $detalles = [
                // Debe: Clientes (activo aumenta)
                [
                    'Id_Cuenta' => $cuentaClientes->Id_Cuenta,
                    'Debe' => $factura->Total,
                    'Haber' => 0,
                ],
                // Haber: Ventas (ingreso)
                [
                    'Id_Cuenta' => $cuentaVentas->Id_Cuenta,
                    'Debe' => 0,
                    'Haber' => $totalSinIGV,
                ],
                // Haber: IGV por pagar
                [
                    'Id_Cuenta' => $cuentaIGV->Id_Cuenta,
                    'Debe' => 0,
                    'Haber' => $igv,
                ],
            ];

            $glosa = "Factura de venta #{$factura->Id_Factura} - Orden #{$factura->Id_Orden}";

            return ContabilidadService::crearAsiento(
                $periodo->Id_Periodo,
                $factura->Fecha,
                $glosa,
                $detalles
            );
        });
    }

    /**
     * Genera asiento contable para un pago recibido
     */
    public static function registrarPago(Pago $pago)
    {
        return DB::transaction(function () use ($pago) {
            $periodo = Periodo::where('Año', $pago->Fecha->year)
                              ->where('Mes', $pago->Fecha->month)
                              ->first();

            if (!$periodo) {
                throw new \Exception("Periodo contable no encontrado");
            }

            $cuentaCaja = CuentaContable::where('Codigo', '1041')->first();   // Caja
            $cuentaClientes = CuentaContable::where('Codigo', '1212')->first(); // Cuentas por cobrar

            if (!$cuentaCaja || !$cuentaClientes) {
                throw new \Exception("Faltan cuentas contables para registrar el pago");
            }

            $detalles = [
                // Debe: Caja (activo aumenta)
                [
                    'Id_Cuenta' => $cuentaCaja->Id_Cuenta,
                    'Debe' => $pago->Monto,
                    'Haber' => 0,
                ],
                // Haber: Clientes (activo disminuye)
                [
                    'Id_Cuenta' => $cuentaClientes->Id_Cuenta,
                    'Debe' => 0,
                    'Haber' => $pago->Monto,
                ],
            ];

            $glosa = "Pago recibido - Factura #{$pago->Id_Factura} - {$pago->Metodo}";

            return ContabilidadService::crearAsiento(
                $periodo->Id_Periodo,
                $pago->Fecha,
                $glosa,
                $detalles
            );
        });
    }
}