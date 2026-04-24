<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{  
    protected $table = 'productos';
    protected $primaryKey = 'Id_Producto';
    public $timestamps = true;

    // ✅ Campos que realmente existen en la tabla
    protected $fillable = [
        'Codigo',
        'Nombre',
        'Precio_Compra',
        'Precio_Venta',
        'Id_Categoria',
        'Id_Proveedor'
    ];

    // 🔹 Relaciones
    public function inventario()
    {
        return $this->hasOne(Inventario::class, 'Id_Producto', 'Id_Producto');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'Id_Producto', 'Id_Producto');
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'Id_Producto', 'Id_Producto');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Id_Categoria', 'Id_Categoria');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'Id_Proveedor', 'Id_Proveedor');
    }
}
