<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaCredito extends Model
{
    protected $table = 'notas_credito';
    protected $primaryKey = 'Id_Nota';
    public $timestamps = true;

    protected $fillable = [
        'Id_Factura', 'Fecha', 'Monto', 'Motivo', 'Estado'
    ];

    protected $casts = [
        'Fecha' => 'date',
        'Monto' => 'decimal:2',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'Id_Factura', 'Id_Factura');
    }
}
