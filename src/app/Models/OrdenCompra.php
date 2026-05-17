<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';
    protected $primaryKey = 'Id_Orden_Compra';
    public $timestamps = true;

    protected $fillable = [
        'Id_Proveedor',
        'Id_Almacen',
        'Fecha',
        'Estado',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'Id_Proveedor', 'Id_Proveedor');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'Id_Almacen', 'Id_Almacen');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleOrdenCompra::class, 'Id_Orden_Compra', 'Id_Orden_Compra');
    }

    // 🔹 Accesor para calcular el total dinámicamente
    public function getTotalAttribute()
    {
        return $this->detalles->sum(fn($d) => $d->Cantidad * $d->Costo);
    }


}
