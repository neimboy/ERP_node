<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOrden extends Model
{
    protected $table = 'detalle_orden';
    protected $primaryKey = 'Id_Detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_Orden',
        'Id_Producto',
        'Cantidad',
        'Precio',
        'Precio_Unitario',
        'Costo_Unitario',
        'Descuento',
        'Total',
    ];

    protected $casts = [
        'Precio' => 'decimal:2',
        'Precio_Unitario' => 'decimal:2',
        'Costo_Unitario' => 'decimal:2',
        'Descuento' => 'decimal:2',
        'Total' => 'decimal:2',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'Id_Orden', 'Id_Orden');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }
}
