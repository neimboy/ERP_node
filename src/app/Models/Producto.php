<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'Id_Producto';
    public $timestamps = true;

    protected $fillable = [
        'Codigo',
        'Nombre',
        'Precio_Compra',
        'Precio_Venta',
        'Id_Categoria',
        'Id_Proveedor',
    ];

    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'Id_Producto', 'Id_Producto');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'Id_Producto', 'Id_Producto');
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'Id_Producto', 'Id_Producto');
    }

    public function detallesOrden()
    {
        return $this->hasMany(DetalleOrden::class, 'Id_Producto', 'Id_Producto');
    }
}
