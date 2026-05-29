<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
    protected $table = 'nominas';
    protected $primaryKey = 'Id_Nomina';

    protected $fillable = [
        'Id_Empleado',
        'Id_Periodo',
        'Total_Bruto',
        'Total_Deducciones',
        'Neto_Pagar'
    ];

    // Relación con Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'Id_Empleado');
    }

    // Relación con Periodo
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'Id_Periodo');
    }
}