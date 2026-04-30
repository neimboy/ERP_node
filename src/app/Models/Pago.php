<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'Id_Pago';
    protected $fillable = ['Id_Factura', 'Fecha', 'Monto', 'Metodo'];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'Id_Factura');
    }
}
