<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';
    protected $primaryKey = 'Id_Proyecto';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'Id_Cliente',
        'Nombre',
        'Fecha_Inicio',
        'Fecha_Fin',
        'Estado'
    ];

    public function getRouteKeyName()
    {
        return 'Id_Proyecto';
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id_Cliente');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'Id_Proyecto', 'Id_Proyecto');
    }

    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'asignaciones', 'Id_Proyecto', 'Id_Empleado')
                    ->withPivot('Horas_Asignadas')
                    ->withTimestamps();
    }
}
