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
        'Glosa',
        'Referencia',   // ← NUEVO
        'Tipo_Origen',  // ← NUEVO
        'Id_Origen',    // ← NUEVO
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

    /**
     * Verifica si el asiento fue generado automáticamente.
     */
    public function esAutomatico(): bool
    {
        return $this->Tipo_Origen !== 'Manual' && $this->Tipo_Origen !== null;
    }

    /**
     * Obtiene una etiqueta legible del tipo de origen.
     */
    public function getEtiquetaOrigenAttribute(): string
    {
        return match($this->Tipo_Origen) {
            'Ventas'     => '🏷️ Venta',
            'Inventario' => '📦 Inventario',
            'RRHH'       => '👤 Planilla',
            'Manual'     => '✍️ Manual',
            default      => '📝 ' . ($this->Tipo_Origen ?? 'Desconocido'),
        };
    }

    /**
     * Obtiene un color Bootstrap para el badge según el tipo de origen.
     */
    public function getColorOrigenAttribute(): string
    {
        return match($this->Tipo_Origen) {
            'Ventas'     => 'success',
            'Inventario' => 'warning',
            'RRHH'       => 'info',
            'Manual'     => 'secondary',
            default      => 'light',
        };
    }
}