<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Empleado; 
use App\Models\Contrato;

class Puesto extends Model
{
    protected $table = 'puestos';
    protected $primaryKey = 'Id_Puesto';

    protected $fillable = [
        'Nombre_Puesto', 
        'Salario_Base'
        ];

    /* Un puesto puede tener muchos empleados asignados
    public function empleados()
    {
        // Relación: Un puesto tiene muchos empleados
        // (Modelo, llave foránea en empleados, llave local en puestos)
        return $this->hasMany(Empleado::class, 'Id_Puesto', 'Id_Puesto');
    }

    // Un puesto puede estar en muchos contratos
    public function contratos()
    {
        return $this->hasMany(Contrato::class, 'Id_Puesto', 'Id_Puesto');
    }*/
}
