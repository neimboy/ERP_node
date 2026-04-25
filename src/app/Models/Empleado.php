<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    protected $primaryKey = 'Id_Empleado';
    

    protected $fillable = [
        'DNI',
        'Nombre_Empleado',
        'Correo_Empleado',
        'Telefono',
        'Fecha_Ingreso',
        'Estado'
    ];

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'asignaciones', 'Id_Empleado', 'Id_Proyecto')
                    ->withPivot('Horas_Asignadas')
                    ->withTimestamps();
    }
}
