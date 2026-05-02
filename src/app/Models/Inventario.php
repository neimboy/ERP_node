<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario'; // nombre exacto de la tabla

    protected $primaryKey = ['Id_Producto', 'Id_Almacen']; // clave compuesta
    public $incrementing = false; // porque no es autoincremental
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Producto',
        'Id_Almacen',
        'Cantidad',
        'Stock_Minimo'
    ];

    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'Id_Producto');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'Id_Almacen');
    }
}
