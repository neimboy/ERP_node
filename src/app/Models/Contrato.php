<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;
    // 1. Nombre de la tabla
    protected $table = 'contratos';
    // 2. Definir la llave primaria personalizada
    protected $primaryKey = 'Id_Contrato';
    // 3. Campos que se pueden llenar masivamente
    protected $fillable = [
        'Id_Empleado',
        'Id_Puesto',
        'Fecha_Inicio',
        'Fecha_Fin'
    ];

    /**
     * RELACIONES
     */

    // Relación con Empleado: Un contrato pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'Id_Empleado', 'Id_Empleado');
    }

    // Relación con Puesto: Un contrato pertenece a un puesto
    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'Id_Puesto', 'Id_Puesto');
    }
}