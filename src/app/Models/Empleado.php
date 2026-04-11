<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'Id_Empleado';

    protected $fillable = [
        'DNI',
        'Nombre',
        'Correo',
        'Telefono',
        'Fecha_Ingreso',
        'Estado'
    ];
}
