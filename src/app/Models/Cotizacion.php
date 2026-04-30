<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    public $timestamps = true;

    protected $fillable = [
        'Id_Cliente', 'Titulo', 'Fecha', 'Validez_Dias', 'Estado', 'Total'
    ];

    protected $casts = [
        'Fecha' => 'date',
        'Total' => 'decimal:2',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'Id_Cliente', 'Id');
    }
}
