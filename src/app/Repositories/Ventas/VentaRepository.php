<?php

namespace App\Repositories\Ventas;

use App\Models\Factura;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VentaRepository implements VentaRepositoryInterface
{
    /**
     * Obtener facturas con relaciones importantes usando eager loading.
     * Permite filtrar por cliente, estado o rango de fechas.
     */
    public function getFacturasConRelaciones(array $filters = [], int $perPage = 25)
    {
        $query = Factura::query()
            ->with(['orden', 'orden.cliente', 'pagos']);

        if (!empty($filters['cliente_id'])) {
            $query->whereHas('orden', function ($q) use ($filters) {
                $q->where('Id_Cliente', $filters['cliente_id']);
            });
        }

        if (!empty($filters['estado'])) {
            $query->where('Estado_Pago', $filters['estado']);
        }

        if (!empty($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }

        if (!empty($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        return $query->orderBy('Id_Factura', 'desc')->paginate($perPage);
    }

    public function findFacturaById(int $id): ?Factura
    {
        return Factura::with(['orden', 'orden.cliente', 'pagos'])->where('Id_Factura', $id)->first();
    }
}
