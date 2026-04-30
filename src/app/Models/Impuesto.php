<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    protected $table = 'impuestos';
    public $timestamps = true;

    protected $fillable = [
        'Nombre', 'Porcentaje', 'Tipo', 'Activo'
    ];

    protected $casts = [
        'Porcentaje' => 'decimal:2',
        'Activo' => 'boolean',
    ];
}
