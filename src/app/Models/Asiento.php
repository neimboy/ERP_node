<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asiento extends Model
{
    protected $table = 'asientos';
    protected $primaryKey = 'Id_Asiento';
    protected $fillable = ['Id_Periodo', 'Fecha', 'Glosa'];

    // El asiento pertenece a un periodo
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'Id_Periodo');
    }

    // Un asiento tiene varios movimientos (Debe/Haber)
    public function detalles()
    {
        return $this->hasMany(AsientoDetalle::class, 'Id_Asiento');
    }
}