<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuiaRemision extends Model
{
    protected $table = 'guias_remision';
    protected $primaryKey = 'Id_Guia';
    public $timestamps = true;

    protected $fillable = [
        'Id_Orden', 'Numero_Guia', 'Fecha_Emision', 'Direccion_Origen', 'Direccion_Destino', 'Estado'
    ];

    protected $casts = [
        'Fecha_Emision' => 'date',
    ];

    public function orden()
    {
        return $this->belongsTo(Orden::class, 'Id_Orden', 'Id_Orden');
    }
}
