<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock'];

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }
}
