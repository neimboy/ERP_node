<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    protected $table = 'almacenes'; // nombre exacto de la tabla
    protected $primaryKey = 'Id_Almacen'; // clave primaria
    public $incrementing = true; // autoincremental
    protected $keyType = 'int';

    protected $fillable = [
        'Nombre',
        'Direccion'
    ];

    // Relación con órdenes de compra
    public function ordenesCompra()
    {
        return $this->hasMany(OrdenCompra::class, 'Id_Almacen', 'Id_Almacen');
    }

    // Relación con inventarios (si existe la tabla y modelo)
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'Id_Almacen', 'Id_Almacen');
    }

    // Relación indirecta: productos a través de los detalles de las órdenes de compra
    public function productos()
    {
        return $this->hasManyThrough(
            DetalleOrdenCompra::class,   // modelo intermedio
            OrdenCompra::class,          // modelo padre
            'Id_Almacen',                // FK en ordenes_compra
            'Id_Orden_Compra',           // FK en detalle_orden_compra
            'Id_Almacen',                // PK en almacenes
            'Id_Orden_Compra'            // PK en ordenes_compra
        );
    }
}
