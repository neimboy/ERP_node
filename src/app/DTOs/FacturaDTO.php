<?php

namespace App\DTOs;

class FacturaDTO
{
    public int $id;
    public int $idOrden;
    public float $total;
    public string $estadoPago;
    public array $cliente;
    public array $pagos;

    public function __construct(array $data)
    {
        $this->id = $data['Id_Factura'] ?? 0;
        $this->idOrden = $data['Id_Orden'] ?? 0;
        $this->total = isset($data['Total']) ? (float)$data['Total'] : 0.0;
        $this->estadoPago = $data['Estado_Pago'] ?? '';
        $this->cliente = $data['cliente'] ?? [];
        $this->pagos = $data['pagos'] ?? [];
    }

    public static function fromModel($factura): self
    {
        $data = [
            'Id_Factura' => $factura->Id_Factura,
            'Id_Orden' => $factura->Id_Orden,
            'Total' => $factura->Total,
            'Estado_Pago' => $factura->Estado_Pago ?? '',
            'cliente' => optional(optional($factura->orden)->cliente)->toArray() ?? [],
            'pagos' => $factura->pagos->map(function ($p) { return $p->toArray(); })->toArray(),
        ];

        return new self($data);
    }
}
