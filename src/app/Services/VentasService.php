<?php

namespace App\Services;

use App\Models\Oportunidad;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\Factura;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class VentasService
{
    /**
     * Marca la oportunidad como ganada, crea la orden y factura asociada (todo atómico).
     *
     * @param Oportunidad $oportunidad
     * @return Orden
     */
    public function ganarOportunidad(Oportunidad $oportunidad): Orden
    {
        return DB::transaction(function () use ($oportunidad) {
            // Actualizar estado y fecha
            $oportunidad->update([
                'Estado' => 'Ganada',
                'Fecha_Cierre' => now(),
            ]);

            // Crear orden básica
            $orden = Orden::create([
                'Id_Cliente' => $oportunidad->Id_Cliente,
                'Fecha' => now(),
                'Estado' => 'Pendiente',
            ]);

            // Asignar Id_Orden en la oportunidad si la columna existe
            if (Schema::hasColumn($oportunidad->getTable(), 'Id_Orden')) {
                $oportunidad->Id_Orden = $orden->Id_Orden;
                $oportunidad->save();
            }

            // Crear factura inicial si hay un monto estimado
            $total = $oportunidad->Monto_Estimado ?? 0;
            $factura = Factura::create([
                'Id_Orden' => $orden->Id_Orden,
                'Fecha' => now(),
                'Total' => $total,
                'Estado_Pago' => 'Pendiente_Pago',
            ]);

            // Intentar integración contable si existe
            try {
                if (class_exists('App\\Services\\IntegracionContableService')) {
                    \App\Services\IntegracionContableService::registrarFactura($factura);
                }
            } catch (\Exception $e) {
                Log::error('Error IntegracionContable al ganar oportunidad: ' . $e->getMessage());
            }

            return $orden;
        });
    }

    /**
     * Crea una orden a partir de lineas (Id_Producto, Cantidad) validando stock atómicamente.
     * Devuelve la orden creada.
     *
     * @param int $Id_Cliente
     * @param array $lineas
     * @return Orden
     * @throws ValidationException
     */
    public function crearOrdenConLineas(int $Id_Cliente, array $lineas): Orden
    {
        return DB::transaction(function () use ($Id_Cliente, $lineas) {
            $orden = Orden::create([
                'Id_Cliente' => $Id_Cliente,
                'Fecha' => now(),
                'Estado' => 'Pendiente',
            ]);

            $total = 0;

            foreach ($lineas as $idx => $line) {
                $Id_Producto = (int) ($line['Id_Producto'] ?? 0);
                $cantidad = (int) ($line['cantidad'] ?? $line['Cantidad'] ?? 0);

                // Bloquear fila(s) relacionadas con el producto para evitar carreras
                $producto = Producto::where('Id_Producto', $Id_Producto)->lockForUpdate()->first();

                if (!$producto) {
                    throw ValidationException::withMessages(['lineas.' . $idx => "Producto no encontrado ({$Id_Producto})"]);
                }

                // Intentar leer stock desde la tabla inventario (suma de almacenes)
                $inventarioFilas = DB::table('inventario')->where('Id_Producto', $Id_Producto)->lockForUpdate()->get();
                $stockTotal = $inventarioFilas->sum('Cantidad');

                // Fallback a atributos del producto si no existe inventario
                if ($inventarioFilas->isEmpty()) {
                    $stockTotal = $producto->stock_simulado ?? $producto->stock ?? $producto->Stock ?? 0;
                }

                if ($stockTotal < $cantidad) {
                    throw ValidationException::withMessages(['lineas.' . $idx => "Stock insuficiente para el producto {$Id_Producto}. Disponible: {$stockTotal}, requerido: {$cantidad}"]);
                }

                // Reducir stock en inventario por filas (primero-primero-servir)
                $restar = $cantidad;
                foreach ($inventarioFilas as $fila) {
                    if ($restar <= 0) break;
                    $disp = (int) $fila->Cantidad;
                    $usar = min($disp, $restar);
                    DB::table('inventario')->where(['Id_Producto' => $Id_Producto, 'Id_Almacen' => $fila->Id_Almacen])->update(['Cantidad' => $disp - $usar]);
                    $restar -= $usar;
                }

                // Si no había filas en inventario, actualizar atributo en producto si existe
                if ($inventarioFilas->isEmpty() && isset($producto->stock_simulado)) {
                    $producto->stock_simulado = $stockTotal - $cantidad;
                    $producto->save();
                }

                $precio = $producto->Precio_Venta ?? $producto->precio ?? 0;

                DetalleOrden::create([
                    'Id_Orden' => $orden->Id_Orden,
                    'Id_Producto' => $Id_Producto,
                    'Cantidad' => $cantidad,
                    'Precio' => $precio,
                ]);

                $total += $precio * $cantidad;
            }

            // Crear factura asociada
            $factura = Factura::create([
                'Id_Orden' => $orden->Id_Orden,
                'Fecha' => now(),
                'Total' => $total,
                'Estado_Pago' => 'Pendiente_Pago',
            ]);

            try {
                if (class_exists('App\\Services\\IntegracionContableService')) {
                    \App\Services\IntegracionContableService::registrarFactura($factura);
                }
            } catch (\Exception $e) {
                Log::error('Integracion contable error al crear orden: ' . $e->getMessage());
            }

            return $orden;
        });
    }
}
