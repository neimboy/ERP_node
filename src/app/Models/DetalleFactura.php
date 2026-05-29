<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = 'detalle_facturas';
    protected $primaryKey = 'Id_Detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_Factura', 'Id_Producto', 'Cantidad', 'Precio_Unitario', 'Costo_Unitario', 'Descuento', 'Subtotal', 'Total'
    ];

    protected $casts = [
        'Precio_Unitario' => 'decimal:2',
        'Costo_Unitario' => 'decimal:2',
        'Descuento' => 'decimal:2',
        'Subtotal' => 'decimal:2',
        'Total' => 'decimal:2',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'Id_Factura', 'Id_Factura');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }
}
