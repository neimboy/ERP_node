<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsientoDetalle extends Model
{
    protected $table = 'asiento_detalle';
    protected $primaryKey = 'Id_Detalle';
    protected $fillable = ['Id_Asiento', 'Id_Cuenta', 'Debe', 'Haber'];

    // El detalle pertenece a un asiento principal
    public function asiento()
    {
        return $this->belongsTo(Asiento::class, 'Id_Asiento');
    }

    // El detalle usa una cuenta específica
    public function cuenta()
    {
        return $this->belongsTo(CuentaContable::class, 'Id_Cuenta');
    }
}