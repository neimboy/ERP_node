<?php

namespace App\Repositories\Ventas;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Factura;

interface VentaRepositoryInterface
{
    /**
     * Obtener facturas con relaciones importantes (orden, cliente, pagos)
     * con paginación y filtros básicos.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator|Factura[]
     */
    public function getFacturasConRelaciones(array $filters = [], int $perPage = 25);

    /**
     * Buscar una factura por Id con relaciones cargadas.
     */
    public function findFacturaById(int $id): ?Factura;
}
