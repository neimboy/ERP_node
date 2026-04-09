<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $fillable = [
        'Nombre',
        'Fecha_Inicio',
        'Fecha_Fin',
        'Estado'
    ];
}
