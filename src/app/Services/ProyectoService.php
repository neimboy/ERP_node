<?php

namespace App\Services;

use App\Models\Proyecto;
use App\Models\ProyectoProducto;
use App\Models\ProyectoGasto;
use App\Models\Inventario;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Notificacion;
use Illuminate\Support\Facades\DB;

class ProyectoService
{
    public function crearProyectoProduccion(array $data, array $productos): Proyecto
    {
        return DB::transaction(function () use ($data, $productos) {
            $data['Tipo'] = 'produccion';
            $maxId = Proyecto::max('Id_Proyecto') ?? 0;
            $data['Id_Proyecto'] = $maxId + 1;
            $proyecto = Proyecto::create($data);

            foreach ($productos as $item) {
                $productoId = $item['Id_Producto'];
                $cantidad = $item['Cantidad'];

                ProyectoProducto::create([
                    'Id_Proyecto' => $proyecto->Id_Proyecto,
                    'Id_Producto' => $productoId,
                    'Cantidad' => $cantidad,
                ]);

                $this->disminuirStock($productoId, $cantidad, $proyecto->Id_Proyecto);
            }

            return $proyecto;
        });
    }

    public function crearProyectoServicio(array $data, array $gastos): Proyecto
    {
        return DB::transaction(function () use ($data, $gastos) {
            $data['Tipo'] = 'servicio';
            $maxId = Proyecto::max('Id_Proyecto') ?? 0;
            $data['Id_Proyecto'] = $maxId + 1;
            $proyecto = Proyecto::create($data);

            foreach ($gastos as $item) {
                ProyectoGasto::create([
                    'Id_Proyecto' => $proyecto->Id_Proyecto,
                    'Descripcion' => $item['Descripcion'],
                    'Monto' => $item['Monto'],
                ]);
            }

            return $proyecto;
        });
    }

    public function actualizarProyectoProduccion(Proyecto $proyecto, array $data, array $nuevosProductos): Proyecto
    {
        return DB::transaction(function () use ($proyecto, $data, $nuevosProductos) {
            $proyecto->update($data);

            foreach ($nuevosProductos as $item) {
                $productoId = $item['Id_Producto'];
                $cantidad = $item['Cantidad'];

                $existente = ProyectoProducto::where('Id_Proyecto', $proyecto->Id_Proyecto)
                    ->where('Id_Producto', $productoId)
                    ->first();

                if ($existente) {
                    $existente->increment('Cantidad', $cantidad);
                } else {
                    ProyectoProducto::create([
                        'Id_Proyecto' => $proyecto->Id_Proyecto,
                        'Id_Producto' => $productoId,
                        'Cantidad' => $cantidad,
                    ]);
                }

                $this->disminuirStock($productoId, $cantidad, $proyecto->Id_Proyecto);
            }

            return $proyecto->fresh();
        });
    }

    public function actualizarProyectoServicio(Proyecto $proyecto, array $data, array $gastos): Proyecto
    {
        return DB::transaction(function () use ($proyecto, $data, $gastos) {
            $proyecto->update($data);

            $proyecto->gastos()->delete();

            foreach ($gastos as $item) {
                ProyectoGasto::create([
                    'Id_Proyecto' => $proyecto->Id_Proyecto,
                    'Descripcion' => $item['Descripcion'],
                    'Monto' => $item['Monto'],
                ]);
            }

            return $proyecto->fresh();
        });
    }

    public function devolverProductos(Proyecto $proyecto, array $productos): Proyecto
    {
        return DB::transaction(function () use ($proyecto, $productos) {
            foreach ($productos as $item) {
                $productoId = $item['Id_Producto'];
                $cantidad = $item['Cantidad'];

                $pivot = ProyectoProducto::where('Id_Proyecto', $proyecto->Id_Proyecto)
                    ->where('Id_Producto', $productoId)
                    ->first();

                if ($pivot) {
                    if ($pivot->Cantidad <= $cantidad) {
                        $pivot->delete();
                    } else {
                        $pivot->decrement('Cantidad', $cantidad);
                    }
                }

                $this->aumentarStock($productoId, $cantidad, $proyecto->Id_Proyecto);
            }

            return $proyecto->fresh();
        });
    }

    public function productosDisponibles(): array
    {
        $productos = Producto::with(['detallesOrdenCompra', 'detallesOrden', 'movimientos'])->get();
        $result = [];

        foreach ($productos as $producto) {
            $result[] = [
                'Id_Producto' => $producto->Id_Producto,
                'Nombre' => $producto->Nombre,
                'Codigo' => $producto->Codigo,
                'Stock_Total' => $producto->stock(),
                'Precio_Venta' => $producto->Precio_Venta,
            ];
        }

        return $result;
    }

    public function notificarSinStock(int $productoId, int $cantidadRequerida, ?int $proyectoId = null): void
    {
        Notificacion::create([
            'Id_Producto' => $productoId,
            'Cantidad_Requerida' => $cantidadRequerida,
            'Id_Proyecto' => $proyectoId,
            'Mensaje' => "Stock insuficiente para el producto. Se requieren {$cantidadRequerida} unidades.",
        ]);
    }

    private function disminuirStock(int $productoId, int $cantidad, ?int $proyectoId = null): void
    {
        Movimiento::create([
            'Id_Producto' => $productoId,
            'Id_Proyecto' => $proyectoId,
            'Tipo' => 'salida_produccion',
            'Cantidad' => $cantidad,
        ]);

        Inventario::firstOrCreate(
            ['Id_Producto' => $productoId, 'Id_Almacen' => 1],
            ['Cantidad' => 0, 'Stock_Minimo' => 0]
        );

        Inventario::where('Id_Producto', $productoId)
            ->where('Id_Almacen', 1)
            ->decrement('Cantidad', $cantidad);
    }

    private function aumentarStock(int $productoId, int $cantidad, ?int $proyectoId = null): void
    {
        Movimiento::create([
            'Id_Producto' => $productoId,
            'Id_Proyecto' => $proyectoId,
            'Tipo' => 'entrada_devolucion',
            'Cantidad' => $cantidad,
        ]);

        Inventario::firstOrCreate(
            ['Id_Producto' => $productoId, 'Id_Almacen' => 1],
            ['Cantidad' => 0, 'Stock_Minimo' => 0]
        );

        Inventario::where('Id_Producto', $productoId)
            ->where('Id_Almacen', 1)
            ->increment('Cantidad', $cantidad);
    }
}
