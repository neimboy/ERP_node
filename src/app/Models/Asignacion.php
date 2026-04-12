<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    protected $primaryKey = 'Id_Asignacion';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'Id_Empleado',
        'Id_Proyecto',
        'Horas_Asignadas'
    ];

    public function getRouteKeyName()
    {
        return 'Id_Asignacion';
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'Id_Empleado', 'Id_Empleado');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'Id_Proyecto', 'Id_Proyecto');
    }
}
