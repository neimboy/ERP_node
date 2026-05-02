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

    // Relación con Inventario
    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'Id_Almacen');
    }
}
