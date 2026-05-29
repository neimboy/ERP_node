<?php

namespace App\Services;

use App\Models\Contabilidad\Asiento;
use App\Models\Contabilidad\AsientoDetalle;
use App\Models\Contabilidad\CuentaContable;
use App\Models\Contabilidad\Periodo;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\OrdenCompra;
use App\Models\Nomina;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * IntegracionContableService
 *
 * Genera asientos contables automáticos desde los demás módulos del ERP.
 * Se llama desde: FacturaController, PagoController, ComprasController, NominaController.
 *
 * Cuentas PCGE usadas (deben existir en cuenta_contable):
 *   12   → Cuentas por Cobrar Comerciales
 *   40   → Tributos por Pagar (IGV 18%)
 *   70   → Ventas de Bienes / Servicios
 *   10   → Caja y Bancos
 *   60   → Compras / Costo de Ventas
 *   42   → Cuentas por Pagar Comerciales
 *   62   → Gastos de Personal (Nómina)
 *   41   → Remuneraciones por Pagar
 */
class IntegracionContableService
{
    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS PRIVADOS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Devuelve el período contable abierto que coincide con la fecha dada.
     * Si no existe, lanza una excepción con mensaje claro.
     */
    private static function obtenerPeriodo(string $fecha): Periodo
    {
        [$año, $mes] = explode('-', substr($fecha, 0, 7));

        $periodo = Periodo::where('Año', (int) $año)
            ->where('Mes', (int) $mes)
            ->where('Estado', 'Abierto')
            ->first();

        if (! $periodo) {
            throw new \RuntimeException(
                "No existe un período contable abierto para {$mes}/{$año}. " .
                "Crea o abre el período antes de registrar movimientos."
            );
        }

        return $periodo;
    }

    /**
     * Busca una cuenta contable por código. Lanza excepción si no existe.
     */
    private static function cuenta(string $codigo): CuentaContable
    {
        $cuenta = CuentaContable::where('Codigo', $codigo)->first();

        if (! $cuenta) {
            throw new \RuntimeException(
                "La cuenta contable con código '{$codigo}' no existe en el Plan de Cuentas. " .
                "Agrégala antes de continuar."
            );
        }

        return $cuenta;
    }

    /**
     * Crea el asiento y sus líneas en una sola transacción.
     *
     * @param  Periodo  $periodo
     * @param  string   $fecha
     * @param  string   $glosa
     * @param  array[]  $lineas  [['cuenta' => '10', 'debe' => 0, 'haber' => 500], ...]
     */
    private static function crearAsiento(
        Periodo $periodo,
        string  $fecha,
        string  $glosa,
        array   $lineas
    ): Asiento {
        // Validar partida doble antes de persistir
        $totalDebe  = collect($lineas)->sum('debe');
        $totalHaber = collect($lineas)->sum('haber');

        if (abs($totalDebe - $totalHaber) > 0.01) {
            throw new \RuntimeException(
                "El asiento automático no cuadra (Debe: {$totalDebe} ≠ Haber: {$totalHaber}). " .
                "Glosa: {$glosa}"
            );
        }

        return DB::transaction(function () use ($periodo, $fecha, $glosa, $lineas) {
            $asiento = Asiento::create([
                'Id_Periodo' => $periodo->Id_Periodo,
                'Fecha'      => $fecha,
                'Glosa'      => $glosa,
            ]);

            foreach ($lineas as $linea) {
                AsientoDetalle::create([
                    'Id_Asiento' => $asiento->Id_Asiento,
                    'Id_Cuenta'  => self::cuenta($linea['cuenta'])->Id_Cuenta,
                    'Debe'       => round((float) $linea['debe'],  2),
                    'Haber'      => round((float) $linea['haber'], 2),
                ]);
            }

            return $asiento;
        });
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VENTAS — Factura emitida
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Registra el asiento de EMISIÓN de factura de venta.
     *
     * Asiento:
     *   DEBE   12  Cuentas por Cobrar        Total con IGV
     *   HABER  70  Ventas                    Base imponible
     *   HABER  40  IGV por Pagar             IGV (18%)
     *
     * Llamado desde: FacturaController::store()
     */
    public static function registrarFactura(Factura $factura): Asiento
    {
        $factura->loadMissing('orden.cliente');

        $total    = (float) $factura->Total;
        $base     = round($total / 1.18, 2);      // base imponible sin IGV
        $igv      = round($total - $base, 2);

        $cliente  = $factura->orden->cliente->Nombre ?? 'Cliente';
        $periodo  = self::obtenerPeriodo($factura->Fecha);

        $asiento = self::crearAsiento(
            $periodo,
            $factura->Fecha,
            "Factura #{$factura->Id_Factura} – Venta a {$cliente}",
            [
                ['cuenta' => '12', 'debe' => $total, 'haber' => 0   ],
                ['cuenta' => '70', 'debe' => 0,      'haber' => $base],
                ['cuenta' => '40', 'debe' => 0,      'haber' => $igv ],
            ]
        );

        Log::info("[Contabilidad] Asiento #{$asiento->Id_Asiento} creado por Factura #{$factura->Id_Factura}");

        return $asiento;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // VENTAS — Pago recibido
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Registra el asiento de COBRO de una factura.
     *
     * Asiento:
     *   DEBE   10  Caja y Bancos             Monto cobrado
     *   HABER  12  Cuentas por Cobrar        Monto cobrado
     *
     * Llamado desde: PagoController::store()
     */
    public static function registrarPago(Pago $pago): Asiento
    {
        $pago->loadMissing('factura.orden.cliente');

        $monto   = (float) $pago->Monto;
        $cliente = $pago->factura->orden->cliente->Nombre ?? 'Cliente';
        $periodo = self::obtenerPeriodo($pago->Fecha);

        $asiento = self::crearAsiento(
            $periodo,
            $pago->Fecha,
            "Cobro Pago #{$pago->Id_Pago} – Factura #{$pago->Id_Factura} – {$cliente} ({$pago->Metodo})",
            [
                ['cuenta' => '10', 'debe' => $monto, 'haber' => 0     ],
                ['cuenta' => '12', 'debe' => 0,      'haber' => $monto],
            ]
        );

        // Actualizar estado de la factura si ya fue pagada en su totalidad
        self::actualizarEstadoFactura($pago->factura);

        Log::info("[Contabilidad] Asiento #{$asiento->Id_Asiento} creado por Pago #{$pago->Id_Pago}");

        return $asiento;
    }

    /**
     * Marca la factura como 'Pagado' si la suma de pagos cubre el total.
     */
    private static function actualizarEstadoFactura(Factura $factura): void
    {
        $factura->loadMissing('pagos');
        $totalPagado = $factura->pagos->sum('Monto');

        if ($totalPagado >= $factura->Total) {
            $factura->update(['Estado_Pago' => 'Pagado']);
        } elseif ($totalPagado > 0) {
            $factura->update(['Estado_Pago' => 'Parcial']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // COMPRAS — Orden de compra recibida
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Registra el asiento de RECEPCIÓN de una orden de compra.
     *
     * Asiento:
     *   DEBE   60  Compras / Costo           Base imponible
     *   DEBE   40  IGV Crédito Fiscal        IGV (18%)
     *   HABER  42  Cuentas por Pagar         Total con IGV
     *
     * Llamado desde: ComprasController::updateEstado() cuando Estado → 'Recibida'
     */
    public static function registrarCompra(OrdenCompra $compra): Asiento
    {
        $compra->loadMissing('detalles.producto', 'proveedor');

        // Calcular total desde los detalles (Cantidad * Costo)
        $total = $compra->detalles->sum(fn($d) => $d->Cantidad * $d->Costo);
        $base  = round($total / 1.18, 2);
        $igv   = round($total - $base, 2);

        $proveedor = $compra->proveedor->Nombre ?? 'Proveedor';
        $periodo   = self::obtenerPeriodo($compra->Fecha);

        $asiento = self::crearAsiento(
            $periodo,
            $compra->Fecha,
            "Compra OC #{$compra->Id_Orden_Compra} – {$proveedor}",
            [
                ['cuenta' => '60', 'debe' => $base,  'haber' => 0    ],
                ['cuenta' => '40', 'debe' => $igv,   'haber' => 0    ],
                ['cuenta' => '42', 'debe' => 0,       'haber' => $total],
            ]
        );

        Log::info("[Contabilidad] Asiento #{$asiento->Id_Asiento} creado por OrdenCompra #{$compra->Id_Orden_Compra}");

        return $asiento;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // RRHH — Nómina pagada
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Registra el asiento de PAGO DE NÓMINA.
     *
     * Asiento:
     *   DEBE   62  Gastos de Personal        Monto bruto
     *   HABER  41  Remuneraciones por Pagar  Monto bruto
     *
     * Y el asiento de PAGO EFECTIVO:
     *   DEBE   41  Remuneraciones por Pagar  Monto neto
     *   HABER  10  Caja y Bancos             Monto neto
     *
     * Llamado desde: NominaController::store() (cuando se implemente)
     *
     * @param  object  $nomina  Modelo Nomina con campos: Fecha, Monto_Bruto, Monto_Neto
     */
    public static function registrarNomina(object $nomina): array
    {
        $periodo   = self::obtenerPeriodo($nomina->Fecha);
        $bruto     = (float) ($nomina->Monto_Bruto ?? $nomina->Monto ?? 0);
        $neto      = (float) ($nomina->Monto_Neto  ?? $nomina->Monto ?? 0);
        $referencia = $nomina->Id_Nomina ?? '—';

        // Asiento 1: Provisión de nómina
        $asientoProvision = self::crearAsiento(
            $periodo,
            $nomina->Fecha,
            "Provisión Nómina #{$referencia}",
            [
                ['cuenta' => '62', 'debe' => $bruto, 'haber' => 0     ],
                ['cuenta' => '41', 'debe' => 0,      'haber' => $bruto],
            ]
        );

        // Asiento 2: Pago efectivo de nómina
        $asientoPago = self::crearAsiento(
            $periodo,
            $nomina->Fecha,
            "Pago Nómina #{$referencia}",
            [
                ['cuenta' => '41', 'debe' => $neto, 'haber' => 0    ],
                ['cuenta' => '10', 'debe' => 0,     'haber' => $neto],
            ]
        );

        Log::info("[Contabilidad] Asientos #{$asientoProvision->Id_Asiento} y #{$asientoPago->Id_Asiento} creados por Nómina #{$referencia}");

        return [$asientoProvision, $asientoPago];
    }
}