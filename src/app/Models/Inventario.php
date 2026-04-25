<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'Id_Producto',
        'Id_Almacen',
        'Cantidad',
        'Stock_Minimo',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto', 'Id_Producto');
    }
}
