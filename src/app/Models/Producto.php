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
    public function inventarios()
    {
        // Un producto puede estar en varios almacenes
        return $this->hasMany(Inventario::class, 'Id_Producto', 'Id_Producto');
    }

    public function movimientos()
    {
        // Relación agregada por tu compañero
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

    public function detallesOrdenCompra()
    {
        return $this->hasMany(DetalleOrdenCompra::class, 'Id_Producto', 'Id_Producto');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Id_Categoria', 'Id_Categoria');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'Id_Proveedor', 'Id_Proveedor');
    }


    public function stockEnAlmacen($almacenId)
    {
        // Entradas filtradas por almacén y estado Recibida
        $entradas = $this->detallesOrdenCompra()
            ->whereHas('ordenCompra', function($q) use ($almacenId) {
                $q->where('Id_Almacen', $almacenId)
                ->where('Estado', 'Recibida'); // ✅ solo recibidas
            })
            ->sum('Cantidad');

        // Salidas filtradas por estado Confirmada
        $salidas = $this->detallesOrden()
            ->whereHas('orden', function($q) {
                $q->where('Estado', 'Confirmada'); // ✅ solo confirmadas
            })
            ->sum('Cantidad');

        return $entradas - $salidas;
    }

    // En Producto.php
    public function getStockAttribute()
    {
        $entradas = $this->detallesOrdenCompra()
            ->whereHas('ordenCompra', function($q) {
                $q->where('Estado', 'Recibida'); // solo compras recibidas
            })
            ->sum('Cantidad');

        $salidas = $this->detallesOrden()
            ->whereHas('orden', function($q) {
                $q->where('Estado', 'Confirmada'); // solo ventas confirmadas
            })
            ->sum('Cantidad');

        return $entradas - $salidas;
    }
    public function sinStock()
    {
        return $this->stock <= 0;
    }
}
