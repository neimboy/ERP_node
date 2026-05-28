<?php

namespace App\Models\Contabilidad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsientoDetalle extends Model
{
    protected $table = 'asiento_detalle';
    protected $primaryKey = 'Id_Detalle'; // ← nombre real en la migración
    public $timestamps = true;

    protected $fillable = [
        'Id_Asiento',
        'Id_Cuenta',
        'Debe',
        'Haber',
    ];

    protected $casts = [
        'Debe'  => 'decimal:2',
        'Haber' => 'decimal:2',
    ];

    public function asiento(): BelongsTo
    {
        return $this->belongsTo(Asiento::class, 'Id_Asiento', 'Id_Asiento');
    }

    public function cuenta(): BelongsTo
    {
        return $this->belongsTo(CuentaContable::class, 'Id_Cuenta', 'Id_Cuenta');
    }
}