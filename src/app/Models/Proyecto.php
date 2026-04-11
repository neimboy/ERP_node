<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $fillable = [
        'Id_Cliente',
        'Nombre',
        'Fecha_Inicio',
        'Fecha_Fin',
        'Estado'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id_Cliente');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'Id_Proyecto', 'Id_Proyecto');
    }
}
