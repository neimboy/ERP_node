<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleOrdenCompra extends Model
{
    protected $table = 'detalle_orden_compra';
    protected $primaryKey = 'Id_Detalle';
    public $timestamps = true;

    protected $fillable = [
        'Id_Orden_Compra',
        'Id_Producto',
        'Cantidad',
        'Costo'
    ];

    // 🔹 Relaciones
    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'Id_Orden_Compra', 'Id_Orden_Compra');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }
}
