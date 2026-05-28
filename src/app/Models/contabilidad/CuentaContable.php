<?php

namespace App\Models\Contabilidad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuentaContable extends Model
{
    protected $table = 'cuenta_contable'; // Singular según la validación del controller
    protected $primaryKey = 'Id_Cuenta';
    public $timestamps = true;

    protected $fillable = [
        'Codigo',
        'Nombre_Cuenta',
        'Tipo'
    ];

    /**
     * Relación: Una cuenta puede aparecer en muchos detalles de asientos.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(AsientoDetalle::class, 'Id_Cuenta', 'Id_Cuenta');
    }
}