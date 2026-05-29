<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;
    protected $table = 'contratos';
    protected $primaryKey = 'Id_Contrato';
    protected $fillable = [
        'Id_Empleado',
        'Id_Puesto',
        'Fecha_Inicio',
        'Fecha_Fin',
        'Salario_Base'
    ];

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'Id_Empleado', 'Id_Empleado');
    }

    // Relación con Puesto
    public function puesto()
    {
        return $this->belongsTo(Puesto::class, 'Id_Puesto', 'Id_Puesto');
    }
}