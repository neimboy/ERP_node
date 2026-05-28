<?php

namespace App\Models\Contabilidad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asiento extends Model
{
    protected $table = 'asientos';
    protected $primaryKey = 'Id_Asiento';
    public $timestamps = true;

    protected $fillable = [
        'Id_Periodo',
        'Fecha',
        'Glosa'
    ];

    /**
     * Relación: El asiento pertenece a un período específico.
     */
    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'Id_Periodo', 'Id_Periodo');
    }

    /**
     * Relación: Un asiento tiene muchas líneas de detalle (Debe/Haber).
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(AsientoDetalle::class, 'Id_Asiento', 'Id_Asiento');
    }
}