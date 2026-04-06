<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaContable extends Model
{
    protected $table = 'cuenta_contable';
    protected $primaryKey = 'Id_Cuenta';
    protected $fillable = ['Codigo', 'Nombre_Cuenta', 'Tipo'];

    // Una cuenta aparece en muchos detalles de asientos
    public function detalles()
{
    return $this->hasMany(AsientoDetalle::class, 'Id_Cuenta')
                ->with('asiento')
                ->orderBy('Id_Asiento');
}
}