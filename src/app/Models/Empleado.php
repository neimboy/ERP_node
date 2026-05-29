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
        'Estado', 
        'Id_Puesto'
    ];

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class, 'asignaciones', 'Id_Empleado', 'Id_Proyecto')
                    ->withPivot('Horas_Asignadas')
                    ->withTimestamps();
    }

    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'Id_Puesto');
    }

    public function nominas()
    {
        return $this->hasMany(Nomina::class, 'Id_Empleado');
    }
}
