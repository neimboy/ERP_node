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
        'Costo', // 🔹 este es tu campo real
    ];

    // Relación con OrdenCompra
    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class, 'Id_Orden_Compra', 'Id_Orden_Compra');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }

    // 🔹 Accesor para subtotal
    public function getSubtotalAttribute()
    {
        return $this->Cantidad * $this->Costo;
    }


}
